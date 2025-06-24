<template>
  <div class="space-y-10">
    <!-- Upload Section -->
    <div>
      <div
        class="rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all duration-300 p-4 cursor-pointer bg-white dark:bg-gray-800"
      >
        <FilePond
          name="image"
          ref="pond"
          label-idle="📂 Click to choose image or drag here"
          @init="filepondInitialized"
          @processfile="handleProcessedFile"
          accepted-file-types="image/*"
          allow-multiple
          max-file-size="2MB"
          class="mb-2"
        />
      </div>
      <p class="text-sm text-center text-gray-500 dark:text-gray-400 mt-2">
        Max file size: <span class="font-medium">2MB</span> | Formats:
        <span class="font-medium">JPG, PNG, GIF</span>
      </p>
    </div>

    <!-- Gallery -->
    <div>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <div
          v-for="(image, index) in images"
          :key="image"
          class="relative group overflow-hidden rounded-xl border border-gray-200 dark:border-gray-600 shadow-md bg-white dark:bg-gray-800 transition"
        >
          <img
            :src="`/storage/${image}`"
            alt="Uploaded image"
            class="w-full h-56 object-cover transition-transform duration-300 group-hover:scale-105 cursor-pointer"
            @click="openViewer(index)"
          />
          <button
            @click="confirmDelete(image, index)"
            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-md"
            title="Delete Image"
          >
            ✕
          </button>
        </div>
      </div>
    </div>

    <!-- Image Viewer Modal -->
    <div v-if="isViewerOpen" class="fixed inset-0 bg-black bg-opacity-80 z-50 flex items-center justify-center">
      <button
        @click="closeViewer"
        class="absolute top-4 right-4 text-white text-3xl font-bold hover:text-red-500"
      >
        ×
      </button>

      <button
        v-if="currentImageIndex > 0"
        @click="prevImage"
        class="absolute left-4 text-white text-4xl font-bold hover:text-blue-400"
      >
        ‹
      </button>

      <img
        :src="`/storage/${images[currentImageIndex]}`"
        alt="Viewing image"
        class="max-w-[90%] max-h-[80vh] rounded-lg shadow-lg object-contain"
      />

      <button
        v-if="currentImageIndex < images.length - 1"
        @click="nextImage"
        class="absolute right-4 text-white text-4xl font-bold hover:text-blue-400"
      >
        ›
      </button>
    </div>
  </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>

<script>
import vueFilePond, { setOptions } from 'vue-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

import Swal from 'sweetalert2';
import { useToast } from 'vue-toastification';

const toast = useToast();
let serverMessage = {};

(async () => {
  try {
    const response = await axios.get('/csrf-token');
    const newToken = response.data.token;


    console.log("token=",newToken, ", dd=",document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),);

    // Update FilePond server headers with new token
    setOptions({
      server: {
        process: {
          url: '/upload',
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
          },
          onError: (response) => {
            serverMessage = JSON.parse(response);
          },
        },
      },
    });

    // console.log('✅ FilePond CSRF token refreshed');
  } catch (e) {
    // console.error('❌ Failed to refresh CSRF token for FilePond', e);
  }
})();


const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);

export default {
  components: {
    FilePond,
  },
  data() {
    return {
      images: [],
      isViewerOpen: false,
      currentImageIndex: 0,
    };
  },
mounted() {
  axios
    .get('/images')
    .then((response) => {
      this.images = response.data;
    })
    .catch((error) => {
      console.error(error);
    });

  window.addEventListener('keydown', this.handleKeydown);
},
beforeUnmount() {
  window.removeEventListener('keydown', this.handleKeydown);
},
methods: {
  filepondInitialized() {
    console.log('FilePond initialized');
  },

  handleProcessedFile(error, file) {
    if (error) return console.error(error);
    this.images.unshift(file.serverId);
    toast.success('Image uploaded successfully!');
  },

  async confirmDelete(imagePath, index) {
    const result = await Swal.fire({
      title: 'Are you sure?',
      text: 'You won’t be able to revert this!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        popup: 'rounded-lg',
      },
    });

    if (result.isConfirmed) {
      axios
        .delete(`/images/${encodeURIComponent(imagePath.replace('images/', ''))}`)
        .then(() => {
          this.images.splice(index, 1);
          toast.success('Image deleted successfully!');
          if (this.isViewerOpen && this.currentImageIndex === index) {
            this.closeViewer();
          }
        })
        .catch(() => {
          toast.error('Failed to delete image.');
        });
    }
  },

  openViewer(index) {
    this.currentImageIndex = index;
    this.isViewerOpen = true;
  },

  closeViewer() {
    this.isViewerOpen = false;
  },

  prevImage() {
    if (this.currentImageIndex > 0) {
      this.currentImageIndex--;
    }
  },

  nextImage() {
    if (this.currentImageIndex < this.images.length - 1) {
      this.currentImageIndex++;
    }
  },

  handleKeydown(event) {
    if (!this.isViewerOpen) return;

    switch (event.key) {
      case 'ArrowLeft':
        this.prevImage();
        break;
      case 'ArrowRight':
        this.nextImage();
        break;
      case 'Escape':
        this.closeViewer();
        break;
    }
  },
}

};
</script>
