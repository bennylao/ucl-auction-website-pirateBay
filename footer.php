<!-- If you like, you can put a page footer (something that should show up at
     the bottom of every page, such as helpful links, layout, etc.) here. -->


<!-- Bootstrap core JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function markAsRead(itemId, userId, type) {
        $.ajax({
            url: 'isRead.php',
            type: 'POST',
            data: {
                itemId: itemId,
                userId: userId,
                type: type
            },
            success: function(response) {
                console.log("Update successful");
                $("#notification_" + itemId).hide();
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + error);
            }
        });
    }

</script>
</body>

</html>