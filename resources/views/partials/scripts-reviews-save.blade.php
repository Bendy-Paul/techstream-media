<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Save Button Logic
        const saveButtons = document.querySelectorAll('.btn-save-item');
        
        saveButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.disabled) return;
                
                const itemId = this.dataset.itemId;
                const itemType = this.dataset.itemType;
                const originalText = this.querySelector('.save-text').textContent;
                const originalHtml = this.innerHTML;
                
                // Disable button
                this.classList.add('disabled');
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                
                fetch("{{ route('saved-items.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        item_type: itemType
                    })
                })
                .then(response => {
                     if (!response.ok) {
                        return response.json().then(err => { throw err; });
                     }
                     return response.json();
                })
                .then(data => {
                    this.classList.remove('disabled');
                    if (data.status === 'saved') {
                        this.classList.add('active', 'btn-primary', 'text-white');
                        this.classList.remove('btn-outline-primary');
                        this.innerHTML = '<i class="fas fa-bookmark me-1"></i> <span class="save-text">Saved</span>';
                    } else {
                        this.classList.remove('active', 'btn-primary', 'text-white');
                        this.classList.add('btn-outline-primary');
                        this.innerHTML = '<i class="far fa-bookmark me-1"></i> <span class="save-text">Save</span>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.classList.remove('disabled');
                    this.innerHTML = originalHtml;
                    
                    if (error.status === 401 || error.message === 'Unauthenticated.'){
                         alert('Please login to save items.');
                         window.location.href = "{{ route('login') }}";
                    } else if (error.status === 403) {
                         alert('Your email needs to be verified to save items.');
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                });
            });
        });

        // Review Form Logic
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('btn-submit-review');
                const messageDiv = document.getElementById('review-message');
                const originalBtnText = submitBtn.textContent;
                
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
                messageDiv.innerHTML = '';
                
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    if (!response.ok) {
                         return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                    
                    messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    this.reset();
                    
                    // improved user experience: append the new review or reload if easy
                    setTimeout(() => {
                        window.location.reload(); 
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                    
                    let errorMsg = 'An error occurred.';
                    if (error.errors) {
                        errorMsg = Object.values(error.errors).flat().join('<br>');
                    } else if (error.message) {
                        errorMsg = error.message;
                    }
                    
                    messageDiv.innerHTML = `<div class="alert alert-danger">${errorMsg}</div>`;
                });
            });
        }
    });
</script>
