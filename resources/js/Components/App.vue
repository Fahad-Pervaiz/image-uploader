<!-- <template>
    <div class="mt-4">
        <FilePond
            name="image"
            ref="pond"
            label-idle="Click to choose image or drag here"
            @init="filepondInitialized"
            @processfile="handleProcessedFile"
            accepted-file-types="image/*"
            allow-multiple="true"
            max-file-size="1MB"
        />
        <div class="text-center mt-2">
            <p class="text-sm text-gray-500">Max file size: 1MB | Accepted formats: JPG, PNG, GIF</p>
        </div>
        <h2 class="text-2xl font-semibold text-gray-700 text-center mb-4">🖼 Image Gallery</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
            <div
                v-for="(image, index) in images"
                :key="index"
                class="rounded overflow-hidden shadow-md border border-gray-200 bg-white">
                <img
                    :src="`/storage/${image}`"
                    alt="Uploaded image"
                    class="w-full h-48 object-cover hover:scale-105 transform transition-all duration-300"
                />
            </div>
        </div>

    </div>
</template> -->
<!-- <template>
  <div class="space-y-8">
    <div>
      <FilePond
        name="image"
        ref="pond"
        label-idle="📂 Click to choose image or drag here"
        @init="filepondInitialized"
        @processfile="handleProcessedFile"
        accepted-file-types="image/*"
        allow-multiple="true"
        max-file-size="1MB"
        class="mb-2"
      />
      <p class="text-sm text-gray-500 text-center">
        Max file size: <span class="font-medium">1MB</span> | Formats: <span class="font-medium">JPG, PNG, GIF</span>
      </p>
    </div>

    <div>
      <h2 class="text-2xl font-semibold text-center text-gray-700 mb-4">🖼 Image Gallery</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        <div
          v-for="(image, index) in images"
          :key="index"
          class="overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 bg-white">
          <img
            :src="`/storage/${image}`"
            alt="Uploaded image"
            class="w-full h-48 object-cover hover:scale-105 transform transition-transform duration-300"
          />
        </div>
      </div>
    </div>
  </div>
</template> -->
<!-- <template>
  <div class="space-y-8">
    <div>
      <div
        class="rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 p-4 cursor-pointer"
      >
        <FilePond
          name="image"
          ref="pond"
          label-idle="📂 Click to choose image or drag here"
          @init="filepondInitialized"
          @processfile="handleProcessedFile"
          accepted-file-types="image/*"
          allow-multiple="true"
          max-file-size="1MB"
          class="mb-2"
        />
      </div>
      <p class="text-sm text-gray-500 text-center">
        Max file size: <span class="font-medium">1MB</span> | Formats: <span class="font-medium">JPG, PNG, GIF</span>
      </p>
    </div>

    <div>
      <h2 class="text-2xl font-semibold text-center text-gray-700 mb-4">🖼 Image Gallery</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        <div
          v-for="(image, index) in images"
          :key="index"
          class="overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 bg-white">
          <img
            :src="`/storage/${image}`"
            alt="Uploaded image"
            class="w-full h-48 object-cover hover:scale-105 transform transition-transform duration-300"
          />
        </div>
      </div>
    </div>
  </div>
</template> -->
<template>
  <div class="space-y-8">
    <!-- Upload Section -->
    <div>
      <div class="rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-500 hover:bg-blue-50 transition-all duration-300 p-4 cursor-pointer">
        <FilePond
          name="image"
          ref="pond"
          label-idle="📂 Click to choose image or drag here"
          @init="filepondInitialized"
          @processfile="handleProcessedFile"
          accepted-file-types="image/*"
          allow-multiple="true"
          max-file-size="1MB"
          class="mb-2"
        />
      </div>
      <p class="text-sm text-gray-500 text-center">
        Max file size: <span class="font-medium">1MB</span> | Formats: <span class="font-medium">JPG, PNG, GIF</span>
      </p>
    </div>

    <!-- Gallery -->
    <div>
      <h2 class="text-2xl font-semibold text-center text-gray-700 mb-4">🖼 Image Gallery</h2>
      <transition-group
        name="fade"
        tag="div"
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5"
      >
        <div
          v-for="(image, index) in images"
          :key="image"
          class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 border border-gray-100 bg-white"
        >
          <img
            :src="`/storage/${image}`"
            alt="Uploaded image"
            class="w-full h-48 object-cover hover:scale-105 transform transition-transform duration-300"
          />
          <button
            @click="confirmDelete(image, index)"
            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-md transition"
            title="Delete Image"
          >
            ✕
          </button>
        </div>
      </transition-group>
    </div>
  </div>
</template>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
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
    setOptions({
        // Set global options for FilePond
        server: {
            process: {
                url: '/upload',
                onError: (response) => {
                    serverMessage = JSON.parse(response);
                },
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            }

        },
        labelFileProcessingError: () => {
            return serverMessage.error;
        }
    });

    // Initialize FilePond component with the plugin
    const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);


    export default {
        components: {
            FilePond,
        },
        data() {
            return {
                // You can add any data properties here if needed
                images: [],
            };
        },
        mounted() {
        axios.get('/images')
            .then(response => {
            this.images = response.data;
            })
            .catch(error => {
            console.error(error);
            });
        },
        methods: {
            filepondInitialized() {
                // This method is called when FilePond is initialized
                console.log('FilePond initialized');
                console.log('FilePond object:', this.$refs.pond);
            },
            handleProcessedFile(error, file) {
                if (error) {
                    console.error(error);
                    return;
                }
                this.images.unshift(file.serverId);
            },

            async confirmDelete(imagePath, index) {
                console.log("imagePath=",imagePath)


            const result = await Swal.fire({
                title: 'Are you sure?',
                text: 'You won’t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                popup: 'rounded-lg'
                }
            });

                if (result.isConfirmed) {
                    axios.delete(`/images/${encodeURIComponent(imagePath.replace('images/', ''))}`)

                    .then(() => {
                        this.images.splice(index, 1);
                        toast.success('Image deleted successfully!');
                    })
                    .catch(() => {
                        toast.error('Failed to delete image.');
                    });
                }
            },
        },
    }

</script>
