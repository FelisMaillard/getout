// Fonction pour prévisualiser l'image avant upload
function previewProfilePhoto() {
    // Récupérer les éléments du DOM
    const photoInput = document.getElementById('photo');
    const previewContainer = document.getElementById('preview-container');
    const currentPhotoContainer = document.getElementById('current-photo-container');
    const photoPreview = document.getElementById('photo-preview');
    const saveButton = document.getElementById('save-photo-button');

    // Si l'élément n'existe pas, sortir de la fonction
    if (!photoInput || !previewContainer || !photoPreview) return;

    // Afficher le bouton d'enregistrement seulement quand une photo est sélectionnée
    saveButton.style.display = 'none';

    // Écouter le changement de fichier
    photoInput.addEventListener('change', function() {
        // Si un fichier est sélectionné
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // Vérifier si c'est une image
            if (!file.type.match('image.*')) {
                alert('Veuillez sélectionner une image (JPEG, PNG ou WebP)');
                return;
            }

            // Cacher la photo actuelle et montrer la prévisualisation
            if (currentPhotoContainer) {
                currentPhotoContainer.style.display = 'none';
            }
            previewContainer.style.display = 'block';

            // Créer un objet URL pour la prévisualisation
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                // Afficher le bouton d'enregistrement
                saveButton.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            // Si aucun fichier n'est sélectionné, revenir à l'état initial
            if (currentPhotoContainer) {
                currentPhotoContainer.style.display = 'block';
            }
            previewContainer.style.display = 'none';
            saveButton.style.display = 'none';
        }
    });
}

// Initialiser la prévisualisation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', previewProfilePhoto);
