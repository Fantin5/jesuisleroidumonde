
        let selectedImages = [];
        let editingMealId = null;
        let existingImages = [];
        let cropper = null;
        let cropQueue = [];
        let currentCropIndex = 0;
        let currentCropFormat = null;
        let croppedImages = [];
        let currentLanguage = 'en';
        let allMeals = [];
        let currentCategoryFilter = 'all';

        // Check authentication on page load
        window.addEventListener('load', function() {
            checkAuth();
            loadMeals();
            loadComments();
            setupDragDrop();
            setDefaultCropFormat();
            setupCategorySelector();
            
            // Load saved language preference
            const savedLanguage = localStorage.getItem('preferred-language') || 'en';
            if (savedLanguage !== 'en') {
                currentLanguage = savedLanguage;
                updateLanguageToggle();
                translatePage();
            }
        });

        function setupCategorySelector() {
            const categorySelect = document.getElementById('category');
            categorySelect.addEventListener('change', function() {
                // Update visual styling based on category
                categorySelect.className = `category-${this.value}`;
            });
        }

        async function checkAuth() {
            try {
                const response = await fetch('api.php?action=check_auth');
                const result = await response.json();
                if (!result.authenticated) {
                    window.location.href = 'admin.html';
                }
            } catch (error) {
                console.error('Auth check failed:', error);
                window.location.href = 'admin.html';
            }
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                fetch('api.php', {
                    method: 'POST',
                    body: new FormData().append('action', 'logout')
                }).finally(() => {
                    window.location.href = 'admin.html';
                });
            }
        }

        // Enhanced file handling with click and drag support
        function triggerFileInput() {
            document.getElementById('images').click();
        }

        // Handle file selection
        document.getElementById('images').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const imageFiles = files.filter(file => file.type.startsWith('image/'));
            
            if (imageFiles.length === 0) {
                showMessage('Please select valid image files.', 'error');
                return;
            }

            // Start crop workflow
            startCropWorkflow(imageFiles);
        });

        // Drag and drop enhancement
        function setupDragDrop() {
            const fileUpload = document.querySelector('.enhanced-file-upload');
            const fileUploadLabel = document.querySelector('.file-upload-label');
            const fileInput = document.getElementById('images');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                fileUpload.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                fileUpload.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                fileUpload.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            fileUpload.addEventListener('drop', handleDrop, false);
        }

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            document.querySelector('.file-upload-label').classList.add('dragover');
        }

        function unhighlight(e) {
            document.querySelector('.file-upload-label').classList.remove('dragover');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = Array.from(dt.files);
            const imageFiles = files.filter(file => file.type.startsWith('image/'));
            
            if (imageFiles.length > 0) {
                // Update the file input with dropped files
                const fileInput = document.getElementById('images');
                const dataTransfer = new DataTransfer();
                imageFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
                
                startCropWorkflow(imageFiles);
            } else {
                showMessage('Please drop valid image files.', 'error');
            }
        }

        function startCropWorkflow(files) {
            cropQueue = files;
            currentCropIndex = 0;
            setDefaultCropFormat();
            
            if (cropQueue.length > 0) {
                openCropModal(cropQueue[currentCropIndex]);
            }
        }

        function setDefaultCropFormat() {
            currentCropFormat = {
                ratio: 1.333,
                name: 'Gallery',
                width: 800,
                height: 600
            };
        }

        function openCropModal(file) {
            const modal = document.getElementById('cropModal');
            const cropImage = document.getElementById('cropImage');
            const cropTitle = document.getElementById('cropTitle');
            const queueInfo = document.getElementById('cropQueueInfo');
            
            // Update UI
            cropTitle.textContent = `Crop Photo for Gallery Display`;
            queueInfo.textContent = `Processing image ${currentCropIndex + 1} of ${cropQueue.length}`;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                cropImage.src = e.target.result;
                modal.style.display = 'block';
                
                cropImage.onload = function() {
                    initializeCropper();
                };
            };
            reader.readAsDataURL(file);
        }

        function initializeCropper() {
            if (cropper) {
                cropper.destroy();
            }
            
            const cropImage = document.getElementById('cropImage');
            const cropperOptions = {
                viewMode: 1,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                minCropBoxWidth: 100,
                minCropBoxHeight: 75
            };

            // Set aspect ratio if not free crop
            if (currentCropFormat.ratio > 0) {
                cropperOptions.aspectRatio = currentCropFormat.ratio;
            }

            cropper = new Cropper(cropImage, cropperOptions);
        }

        // Format selection for cropping
        document.addEventListener('click', function(e) {
            if (e.target.closest('.crop-format-btn')) {
                const btn = e.target.closest('.crop-format-btn');
                
                // Remove active class from all buttons
                document.querySelectorAll('.crop-format-btn').forEach(b => {
                    b.classList.remove('active');
                });
                
                // Add active class to clicked button
                btn.classList.add('active');
                
                // Update current format
                currentCropFormat = {
                    ratio: parseFloat(btn.dataset.ratio),
                    name: btn.dataset.name,
                    width: parseInt(btn.dataset.width),
                    height: parseInt(btn.dataset.height)
                };
                
                // Update cropper if it exists
                if (cropper && currentCropFormat.ratio > 0) {
                    cropper.setAspectRatio(currentCropFormat.ratio);
                }
            }
        });

        function applyCrop() {
            if (!cropper) {
                showMessage('Cropper not initialized', 'error');
                return;
            }

            const canvas = cropper.getCroppedCanvas({
                width: currentCropFormat.width,
                height: currentCropFormat.height,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            canvas.toBlob(function(blob) {
                // Create cropped image object
                const croppedImage = {
                    blob: blob,
                    url: URL.createObjectURL(blob),
                    name: cropQueue[currentCropIndex].name,
                    format: currentCropFormat.name,
                    size: `${currentCropFormat.width}x${currentCropFormat.height}`,
                    originalFile: cropQueue[currentCropIndex]
                };

                croppedImages.push(croppedImage);
                
                // Move to next image or finish
                currentCropIndex++;
                
                if (currentCropIndex < cropQueue.length) {
                    // Crop next image
                    setTimeout(() => {
                        openCropModal(cropQueue[currentCropIndex]);
                    }, 100);
                } else {
                    // Finished all images
                    finishCropping();
                }
            }, cropQueue[currentCropIndex].type, 0.9);
        }

        function skipCurrentImage() {
            // Add original image to cropped images
            const originalImage = {
                blob: cropQueue[currentCropIndex],
                url: URL.createObjectURL(cropQueue[currentCropIndex]),
                name: cropQueue[currentCropIndex].name,
                format: 'Original',
                size: 'Original size',
                originalFile: cropQueue[currentCropIndex]
            };

            croppedImages.push(originalImage);
            
            // Move to next image or finish
            currentCropIndex++;
            
            if (currentCropIndex < cropQueue.length) {
                openCropModal(cropQueue[currentCropIndex]);
            } else {
                finishCropping();
            }
        }

        function finishCropping() {
            closeCropModal();
            updateCroppedImagesPreview();
            showMessage(`✅ ${croppedImages.length} image(s) processed and ready!`, 'success');
            
            // Reset file input
            document.getElementById('images').value = '';
        }

        function closeCropModal() {
            const modal = document.getElementById('cropModal');
            modal.style.display = 'none';
            
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }

        function updateCroppedImagesPreview() {
            const preview = document.getElementById('croppedImagesPreview');
            preview.innerHTML = '';

            // Show existing images (when editing)
            existingImages.forEach((image, index) => {
                const item = document.createElement('div');
                item.className = 'cropped-image-item';
                item.innerHTML = `
                    <img src="${image}" alt="Existing image">
                    <div class="cropped-image-info">
                        <div class="cropped-image-format">Existing Image</div>
                        <div class="cropped-image-size">Already uploaded</div>
                    </div>
                    <button type="button" class="cropped-image-remove" onclick="removeExistingImage(${index})">×</button>
                `;
                preview.appendChild(item);
            });

            // Show cropped images
            croppedImages.forEach((image, index) => {
                const item = document.createElement('div');
                item.className = 'cropped-image-item';
                item.innerHTML = `
                    <img src="${image.url}" alt="${image.name}">
                    <div class="cropped-image-info">
                        <div class="cropped-image-format">${image.format}</div>
                        <div class="cropped-image-size">${image.size}</div>
                    </div>
                    <button type="button" class="cropped-image-remove" onclick="removeCroppedImage(${index})">×</button>
                `;
                preview.appendChild(item);
            });
        }

        function removeExistingImage(index) {
            // Remove from existing images array
            existingImages.splice(index, 1);
            
            // Update preview
            updateCroppedImagesPreview();
            
            showMessage('🗑️ Existing image removed', 'success');
        }

        function removeCroppedImage(index) {
            // Revoke object URL to free memory
            URL.revokeObjectURL(croppedImages[index].url);
            
            // Remove from array
            croppedImages.splice(index, 1);
            
            // Update preview
            updateCroppedImagesPreview();
            
            showMessage('🗑️ Image removed', 'success');
        }

        // Add missing updateImagePreview function
        function updateImagePreview() {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            existingImages.forEach((image, index) => {
                const item = document.createElement('div');
                item.className = 'image-preview-item';
                item.innerHTML = `
                    <img src="${image}" alt="Existing image">
                    <button type="button" class="remove-image" onclick="removeExistingImage(${index})">×</button>
                `;
                preview.appendChild(item);
            });
        }

        // Update form submission to use bilingual fields and category
        document.getElementById('mealForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitLoading = document.getElementById('submitLoading');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.style.display = 'none';
            submitLoading.style.display = 'block';
            
            try {
                const formData = new FormData();
                formData.append('action', editingMealId ? 'update_meal' : 'add_meal');
                if (editingMealId) {
                    formData.append('id', editingMealId);
                }
                
                // Add bilingual fields and category
                formData.append('title_en', document.getElementById('title_en').value);
                formData.append('title_fr', document.getElementById('title_fr').value);
                formData.append('description_en', document.getElementById('description_en').value);
                formData.append('description_fr', document.getElementById('description_fr').value);
                formData.append('category', document.getElementById('category').value);
                
                // Add existing images (when editing)
                formData.append('existing_images', JSON.stringify(existingImages));
                
                // Add cropped images
                croppedImages.forEach(image => {
                    formData.append('images[]', image.blob, image.name);
                });
                
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    const successMessage = editingMealId 
                        ? (currentLanguage === 'en' ? 'Meal updated successfully!' : 'Plat mis à jour avec succès !')
                        : (currentLanguage === 'en' ? 'Meal added successfully!' : 'Plat ajouté avec succès !');
                    showMessage(successMessage, 'success');
                    resetForm();
                    loadMeals();
                } else {
                    showMessage(result.message || 'Error saving meal', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                const errorMessage = currentLanguage === 'en' 
                    ? 'Error saving meal. Please try again.'
                    : 'Erreur lors de la sauvegarde du plat. Veuillez réessayer.';
                showMessage(errorMessage, 'error');
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                submitText.style.display = 'inline';
                submitLoading.style.display = 'none';
            }
        });

        function resetForm() {
            document.getElementById('mealForm').reset();
            selectedImages = [];
            croppedImages = [];
            existingImages = [];
            editingMealId = null;
            
            // Clear bilingual fields and reset category
            document.getElementById('title_en').value = '';
            document.getElementById('title_fr').value = '';
            document.getElementById('description_en').value = '';
            document.getElementById('description_fr').value = '';
            document.getElementById('category').value = 'cat1';
            document.getElementById('category').className = 'category-cat1';
            
            updateDynamicTranslations();
            document.getElementById('cancelEdit').style.display = 'none';
            updateImagePreview();
            updateCroppedImagesPreview();
            
            // Clean up object URLs
            croppedImages.forEach(image => {
                if (image.url) URL.revokeObjectURL(image.url);
            });
        }

        function cancelEdit() {
            resetForm();
        }

        async function loadMeals() {
            const loading = document.getElementById('loadingMeals');
            const container = document.getElementById('mealsContainer');
            
            loading.style.display = 'block';
            container.innerHTML = '';
            
            try {
                const response = await fetch('api.php?action=meals');
                const meals = await response.json();
                
                allMeals = meals; // Store all meals for filtering
                
                if (meals.length === 0) {
                    const noMealsMessage = currentLanguage === 'en' 
                        ? 'No meals found. Add your first meal above!'
                        : 'Aucun plat trouvé. Ajoutez votre premier plat ci-dessus !';
                    container.innerHTML = `<p style="text-align: center; color: #666; padding: 2rem;">${noMealsMessage}</p>`;
                } else {
                    displayMeals(meals);
                }
            } catch (error) {
                console.error('Error loading meals:', error);
                const errorMessage = currentLanguage === 'en' 
                    ? 'Error loading meals. Please try again.'
                    : 'Erreur lors du chargement des plats. Veuillez réessayer.';
                container.innerHTML = `<p style="text-align: center; color: #dc3545; padding: 2rem;">${errorMessage}</p>`;
            } finally {
                loading.style.display = 'none';
            }
        }

        function displayMeals(meals) {
            const container = document.getElementById('mealsContainer');
            container.innerHTML = '';
            
            // Filter meals based on current category filter
            const filteredMeals = currentCategoryFilter === 'all' 
                ? meals 
                : meals.filter(meal => meal.category === currentCategoryFilter);
            
            filteredMeals.forEach(meal => {
                const mealCard = createMealCard(meal);
                container.appendChild(mealCard);
            });
        }

        function filterByCategory(category) {
            currentCategoryFilter = category;
            
            // Update active filter button
            document.querySelectorAll('.category-filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.category-filter-btn.${category}`).classList.add('active');
            
            // Display filtered meals
            displayMeals(allMeals);
        }

        function createMealCard(meal) {
            const card = document.createElement('div');
            card.className = `meal-card ${meal.category || 'cat1'}`;
            
            const firstImage = meal.images && meal.images.length > 0 ? meal.images[0] : 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg==';
            
            // Get the appropriate language content
            const title = currentLanguage === 'en' 
                ? (meal.title_en || meal.title || 'Untitled Dish')
                : (meal.title_fr || meal.title || 'Plat sans titre');
            
            const description = currentLanguage === 'en' 
                ? (meal.description_en || meal.description || 'No description available')
                : (meal.description_fr || meal.description || 'Aucune description disponible');
            
            const editText = currentLanguage === 'en' ? 'Edit' : 'Modifier';
            const deleteText = currentLanguage === 'en' ? 'Delete' : 'Supprimer';
            
            // Category display
            const categoryNames = {
                'cat1': currentLanguage === 'en' ? 'Cat 1' : 'Cat 1',
                'cat2': currentLanguage === 'en' ? 'Cat 2' : 'Cat 2', 
                'cat3': currentLanguage === 'en' ? 'Cat 3' : 'Cat 3'
            };
            
            const categoryName = categoryNames[meal.category] || 'Cat 1';
            
            card.innerHTML = `
                <div class="meal-card-image">
                    <img src="${firstImage}" alt="${title}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgeG1zbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjBmMGYwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZHk9Ii4zZW0iPk5vIEltYWdlPC90ZXh0Pjwvc3ZnPg=='">
                </div>
                <div class="meal-card-content">
                    <div class="category-badge ${meal.category || 'cat1'}">${categoryName}</div>
                    <div class="meal-card-title">${title}</div>
                    <div class="meal-card-description">${description}</div>
                    <div class="language-preview">
                        <div><strong>🇺🇸:</strong> ${meal.title_en || 'Not set'}</div>
                        <div><strong>🇫🇷:</strong> ${meal.title_fr || 'Not set'}</div>
                    </div>
                    <div class="meal-card-actions">
                        <button onclick="editMeal(${meal.id})" class="btn btn-primary">${editText}</button>
                        <button onclick="deleteMeal(${meal.id})" class="btn btn-danger">${deleteText}</button>
                    </div>
                </div>
            `;
            
            return card;
        }

        async function editMeal(id) {
            try {
                const response = await fetch(`api.php?action=get_meal&id=${id}`);
                const meal = await response.json();
                
                if (meal) {
                    editingMealId = id;
                    existingImages = meal.images || [];
                    selectedImages = [];
                    croppedImages = [];
                    
                    // Populate bilingual fields and category
                    document.getElementById('title_en').value = meal.title_en || meal.title || '';
                    document.getElementById('title_fr').value = meal.title_fr || meal.title || '';
                    document.getElementById('description_en').value = meal.description_en || meal.description || '';
                    document.getElementById('description_fr').value = meal.description_fr || meal.description || '';
                    document.getElementById('category').value = meal.category || 'cat1';
                    document.getElementById('category').className = `category-${meal.category || 'cat1'}`;
                    
                    // Update form elements with translations
                    updateDynamicTranslations();
                    document.getElementById('cancelEdit').style.display = 'inline-block';
                    
                    updateImagePreview();
                    updateCroppedImagesPreview();
                    
                    // Scroll to form
                    document.querySelector('.dashboard-section').scrollIntoView({ behavior: 'smooth' });
                }
            } catch (error) {
                console.error('Error loading meal:', error);
                showLocalizedMessage('Error loading meal for editing', 'Erreur lors du chargement du plat pour modification', 'error');
            }
        }

        function deleteMeal(id) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('confirmDelete').onclick = () => confirmDelete(id);
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        async function confirmDelete(id) {
            try {
                const formData = new FormData();
                formData.append('action', 'delete_meal');
                formData.append('id', id);
                
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showLocalizedMessage('Meal deleted successfully!', 'Plat supprimé avec succès !', 'success');
                    loadMeals();
                    closeDeleteModal();
                    
                    // If we were editing this meal, reset the form
                    if (editingMealId == id) {
                        resetForm();
                    }
                } else {
                    showMessage(result.message || 'Error deleting meal', 'error');
                }
            } catch (error) {
                console.error('Error deleting meal:', error);
                showLocalizedMessage('Error deleting meal. Please try again.', 'Erreur lors de la suppression du plat. Veuillez réessayer.', 'error');
            }
        }

        // Add comments management functions
        async function loadComments() {
            const loading = document.getElementById('loadingComments');
            const container = document.getElementById('commentsContainer');
            
            loading.style.display = 'block';
            container.innerHTML = '';
            
            try {
                const response = await fetch('api.php?action=comments');
                const comments = await response.json();
                
                if (comments.length === 0) {
                    const noCommentsMessage = currentLanguage === 'en' 
                        ? 'No comments found.'
                        : 'Aucun commentaire trouvé.';
                    container.innerHTML = `<p style="text-align: center; color: #666; padding: 2rem;">${noCommentsMessage}</p>`;
                } else {
                    displayComments(comments);
                }
            } catch (error) {
                console.error('Error loading comments:', error);
                const errorMessage = currentLanguage === 'en' 
                    ? 'Error loading comments. Please try again.'
                    : 'Erreur lors du chargement des commentaires. Veuillez réessayer.';
                container.innerHTML = `<p style="text-align: center; color: #dc3545; padding: 2rem;">${errorMessage}</p>`;
            } finally {
                loading.style.display = 'none';
            }
        }

        function displayComments(comments) {
            const container = document.getElementById('commentsContainer');
            container.innerHTML = '';
            
            comments.forEach(comment => {
                const commentCard = createCommentCard(comment);
                container.appendChild(commentCard);
            });
        }

        function createCommentCard(comment) {
            const card = document.createElement('div');
            card.className = 'meal-card';
            card.style.marginBottom = '1rem';
            const name = comment.name_fr || 'Anonymous';
            const commentText = currentLanguage === 'en'
                ? (comment.comment_en || comment.comment_fr || 'No comment')
                : (comment.comment_fr || comment.comment_en || 'Aucun commentaire');
            const editText = currentLanguage === 'en' ? 'Edit' : 'Modifier';
            const deleteText = currentLanguage === 'en' ? 'Delete' : 'Supprimer';
            card.innerHTML = `
                <div class="meal-card-content">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div>
                            <div class="meal-card-title">${name}</div>
                            <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.5rem;">
                                ${new Date(comment.created_at).toLocaleDateString()}
                            </div>
                        </div>
                    </div>
                    <div class="meal-card-description">${commentText}</div>
                    <div class="language-preview">
                        <div><strong>👤:</strong> ${comment.name_fr || 'Not set'}</div>
                        <div><strong>🇺🇸:</strong> ${comment.comment_en || 'Not set'}</div>
                        <div><strong>🇫🇷:</strong> ${comment.comment_fr || 'Not set'}</div>
                    </div>
                    <div class="meal-card-actions" style="margin-top: 1rem;">
                        <button onclick="editComment(${comment.id})" class="btn btn-primary">${editText}</button>
                        <button onclick="deleteComment(${comment.id})" class="btn btn-danger">${deleteText}</button>
                    </div>
                </div>
            `;
            return card;
        }

        // Add comment form functions
        function showAddCommentForm() {
            document.getElementById('addCommentForm').style.display = 'block';
            document.getElementById('new_comment_name_en').focus();
        }

        function hideAddCommentForm() {
            document.getElementById('addCommentForm').style.display = 'none';
            document.getElementById('newCommentForm').reset();
        }

        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName + 'Tab').classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
            
            // Load data based on tab
            if (tabName === 'meals') {
                loadMeals();
            } else if (tabName === 'comments') {
                loadComments();
            }
        }

        // Remove the old functions and update the form handlers
        function showAddCommentForm() {
            // Not needed anymore since form is always visible
        }

        function hideAddCommentForm() {
            // Reset the form instead
            document.getElementById('newCommentForm').reset();
        }

        // Add missing showMessage and showLocalizedMessage functions
        function showMessage(message, type) {
            const messageEl = document.getElementById('message');
            messageEl.textContent = message;
            messageEl.className = `message ${type}`;
            messageEl.style.display = 'block';
            
            setTimeout(() => {
                messageEl.style.display = 'none';
            }, 5000);
        }

        function showLocalizedMessage(enMessage, frMessage, type) {
            const message = currentLanguage === 'en' ? enMessage : frMessage;
            showMessage(message, type);
        }

        // Add missing updateDynamicTranslations function
        function updateDynamicTranslations() {
            // Update form title
           
            const isEditing = editingMealId !== null;
            const formTitle = document.getElementById('formTitle');
            const submitText = document.getElementById('submitText');
            
            if (isEditing) {
                formTitle.setAttribute('data-en', 'Edit Meal');
                formTitle.setAttribute('data-fr', 'Modifier le Plat');
                submitText.setAttribute('data-en', 'Update Meal');
                submitText.setAttribute('data-fr', 'Mettre à Jour le Plat');
            } else {
                formTitle.setAttribute('data-en', 'Add New Meal');
                formTitle.setAttribute('data-fr', 'Ajouter un Nouveau Plat');
                submitText.setAttribute('data-en', 'Add Meal');
                submitText.setAttribute('data-fr', 'Ajouter le Plat');
            }
            
            // Re-translate the page
            translatePage();
        }

        // Add missing toggleLanguage function
        function toggleLanguage() {
            currentLanguage = currentLanguage === 'en' ? 'fr' : 'en';
            localStorage.setItem('preferred-language', currentLanguage);
            updateLanguageToggle();
            translatePage();
        }

        function updateLanguageToggle() {
            const enBtn = document.getElementById('lang-en');
            const frBtn = document.getElementById('lang-fr');
            
            if (currentLanguage === 'en') {
                enBtn.classList.add('active');
                frBtn.classList.remove('active');
            } else {
                frBtn.classList.add('active');
                enBtn.classList.remove('active');
            }
        }

        function translatePage() {
            const elements = document.querySelectorAll(`[data-${currentLanguage}]`);
            elements.forEach(element => {
                const translation = element.getAttribute(`data-${currentLanguage}`);
                if (translation) {
                    element.textContent = translation;
                }
            });

            // Handle placeholders
            const placeholderElements = document.querySelectorAll(`[data-placeholder-${currentLanguage}]`);
            placeholderElements.forEach(element => {
                const placeholder = element.getAttribute(`data-placeholder-${currentLanguage}`);
                if (placeholder) {
                    element.placeholder = placeholder;
                }
            });

            // Update page language attribute
            document.documentElement.lang = currentLanguage;
        }

        async function editComment(id) {
            try {
                const response = await fetch(`api.php?action=get_comment&id=${id}`);
                const comment = await response.json();
                
                if (comment) {
                    document.getElementById('commentId').value = comment.id;
                    document.getElementById('comment_name_fr').value = comment.name_fr || '';
                    document.getElementById('comment_text_en').value = comment.comment_en || '';
                    document.getElementById('comment_text_fr').value = comment.comment_fr || '';
                    document.getElementById('commentModal').style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading comment:', error);
                showLocalizedMessage('Error loading comment for editing', 'Erreur lors du chargement du commentaire pour modification', 'error');
            }
        }

        function deleteComment(id) {
            if (confirm(currentLanguage === 'en' ? 'Are you sure you want to delete this comment?' : 'Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                confirmDeleteComment(id);
            }
        }

        async function confirmDeleteComment(id) {
            try {
                const formData = new FormData();
                formData.append('action', 'delete_comment');
                formData.append('id', id);
                
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showLocalizedMessage('Comment deleted successfully!', 'Commentaire supprimé avec succès !', 'success');
                    loadComments();
                } else {
                    showMessage(result.message || 'Error deleting comment', 'error');
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
                showLocalizedMessage('Error deleting comment. Please try again.', 'Erreur lors de la suppression du commentaire. Veuillez réessayer.', 'error');
            }
        }

        function closeCommentModal() {
            document.getElementById('commentModal').style.display = 'none';
        }

        // Comment form submission
        document.getElementById('commentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData();
                formData.append('action', 'update_comment');
                formData.append('id', document.getElementById('commentId').value);
                formData.append('name_fr', document.getElementById('comment_name_fr').value);
                formData.append('comment_en', document.getElementById('comment_text_en').value);
                formData.append('comment_fr', document.getElementById('comment_text_fr').value);
                
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showLocalizedMessage('Comment updated successfully!', 'Commentaire mis à jour avec succès !', 'success');
                    closeCommentModal();
                    loadComments();
                } else {
                    showMessage(result.message || 'Error updating comment', 'error');
                }
            } catch (error) {
                console.error('Error updating comment:', error);
                showLocalizedMessage('Error updating comment. Please try again.', 'Erreur lors de la mise à jour du commentaire. Veuillez réessayer.', 'error');
            }
        });

        // New comment form submission
        document.getElementById('newCommentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData();
                formData.append('action', 'add_comment');
                formData.append('name_fr', document.getElementById('new_comment_name_fr').value);
                formData.append('comment_en', document.getElementById('new_comment_text_en').value);
                formData.append('comment_fr', document.getElementById('new_comment_text_fr').value);
                
                const response = await fetch('api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showLocalizedMessage('Comment added successfully!', 'Commentaire ajouté avec succès !', 'success');
                    document.getElementById('newCommentForm').reset();
                    loadComments();
                } else {
                    showMessage(result.message || 'Error adding comment', 'error');
                }
            } catch (error) {
                console.error('Error adding comment:', error);
                showLocalizedMessage('Error adding comment. Please try again.', 'Erreur lors de l\'ajout du commentaire. Veuillez réessayer.', 'error');
            }
        });

        // Close modal when clicking outside
        window.onclick = function(event) {
            const cropModal = document.getElementById('cropModal');
            const deleteModal = document.getElementById('deleteModal');
            const commentModal = document.getElementById('commentModal');
            
            if (event.target === cropModal) {
                closeCropModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
            if (event.target === commentModal) {
                closeCommentModal();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const cropModal = document.getElementById('cropModal');
                const deleteModal = document.getElementById('deleteModal');
                const commentModal = document.getElementById('commentModal');
                
                if (cropModal.style.display === 'block') {
                    closeCropModal();
                }
                if (deleteModal.style.display === 'block') {
                    closeDeleteModal();
                }
                if (commentModal.style.display === 'block') {
                    closeCommentModal();
                }
            }
        });
