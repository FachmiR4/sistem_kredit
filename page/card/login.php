
<div id="loginPopup">
    <h4>Login</h4>
    <form action="controller/controller.php?action=login" method="post">
        <input type="text" class="form-control" id="no_kontrak" name="kode_kontrak" hidden>
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <button type="button" class="btn btn-secondary" onclick="closePopup()">Close</button>
    </form>
</div>
<div id="popupOverlay"></div>