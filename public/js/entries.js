document.addEventListener('DOMContentLoaded', function() {
    // Category dropdown functionality
    const categorySelect = document.getElementById('category');
    const newCategoryInput = document.getElementById('new_category_input');
    const newCategoryField = document.getElementById('new_category');
    
    if (categorySelect && newCategoryInput) {
        // Initial state check
        if (categorySelect.value === 'new_category') {
            newCategoryInput.classList.remove('hidden');
        }
        
        // Change event listener
        categorySelect.addEventListener('change', function() {
            if (this.value === 'new_category') {
                newCategoryInput.classList.remove('hidden');
                newCategoryField.focus();
            } else {
                newCategoryInput.classList.add('hidden');
            }
        });
    }
    
    // Form submission handling for categories
    const entryForm = document.querySelector('form');
    if (entryForm) {
        entryForm.addEventListener('submit', function(e) {
            if (categorySelect && categorySelect.value === 'new_category' && newCategoryField) {
                e.preventDefault();
                
                // If new category is selected but empty, show error
                if (!newCategoryField.value.trim()) {
                    alert('Please enter a new category name or select an existing one.');
                    newCategoryField.focus();
                    return;
                }
                
                // Set the real category value to the new category input
                categorySelect.name = '_unused_category';
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'category';
                hiddenInput.value = newCategoryField.value.trim();
                entryForm.appendChild(hiddenInput);
                
                // Continue with form submission
                entryForm.submit();
            }
        });
    }
    
    // Handle tag checkboxes and input field
    const tagCheckboxes = document.querySelectorAll('input[name="selected_tags[]"]');
    const tagInput = document.getElementById('tags');
    
    if (tagCheckboxes.length && tagInput) {
        // When form submits, combine selected checkboxes and new tags
        entryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedTags = [];
            
            // Get all checked tags
            tagCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    selectedTags.push(checkbox.value);
                }
            });
            
            // Process new tags from input field
            if (tagInput.value.trim()) {
                const newTags = tagInput.value.split(',')
                    .map(tag => tag.trim())
                    .filter(tag => tag);
                    
                selectedTags.push(...newTags);
            }
            
            // Replace the tags input with the combined value
            tagInput.value = selectedTags.join(',');
            
            // Remove the checkbox inputs from form submission
            tagCheckboxes.forEach(function(checkbox) {
                checkbox.disabled = true;
            });
            
            // Continue with form submission
            entryForm.submit();
        });
    }
});
