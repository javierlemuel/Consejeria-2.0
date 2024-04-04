document.addEventListener("alpine:init", () => {
    Alpine.data("dropdown", (initialOpenState = false) => ({
        open: initialOpenState,

        toggle() {
            this.open = !this.open;
        },
    }));

    Alpine.data('app', () => ({
            showUploadModal: false,
            formData: {
                file: null,
            },
            openUploadModal() {
                this.showUploadModal = true;
            },
            closeUploadModal() {
                this.showUploadModal = false;
            },
            submitForm() {
                // Aquí puedes realizar acciones con el archivo seleccionado, como enviarlo a un servidor.
                // Luego, cierra el modal.
                if (this.formData.file) {
                    console.log("Archivo seleccionado:", this.formData.file);
                    // Aquí puedes realizar las acciones necesarias con el archivo.
                } else {
                    console.log("Ningún archivo seleccionado.");
                }
                this.showUploadModal = false;
            },
        
        }));
    });