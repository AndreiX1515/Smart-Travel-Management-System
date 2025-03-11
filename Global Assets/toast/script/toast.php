<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="liveToast" class="toast align-items-center text-white border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
                <span id="toast-message"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<!-- ✅ Import Toast Script -->
<script type="module">
    import { checkFlashMessage } from "../Global Assets/toast/js/toast.js";
    checkFlashMessage(); // Show toast if a message exists
</script>
