document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('sts_ticket_attachment');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileNameSpan = document.getElementById('sts-file-name');
            if (this.files.length > 0) {
                fileNameSpan.textContent = this.files[0].name;
            }
        });
    }
});