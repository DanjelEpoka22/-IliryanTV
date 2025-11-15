<?php
// Footer i thjeshtë për admin
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Scripts për admin panel
document.addEventListener('DOMContentLoaded', function() {
    // Kontrollo mesazhet e suksesit/errorit
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
});
</script>
</body>
</html>