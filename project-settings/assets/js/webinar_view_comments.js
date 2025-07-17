$(document).ready(function () {
    const webinarId = getQueryParam('id');
    if (!webinarId) {
        alert('No webinar ID specified.');
        return;
    }

    // Load comments on page load
    loadComments();

    // Open modal button click
    $('#openCommentModalBtn').click(function () {
        clearCommentForm();
        $('#commentModal').modal('show');
    });

    // Submit comment form
    $('#commentForm').submit(function (e) {
        e.preventDefault();
        submitCommentForm();
    });

    // Function to get query param
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Load comments from backend
    function loadComments() {
        $.ajax({
            url: 'backend/webinar_comments.php',
            method: 'GET',
            data: { action: 'list', webinar_id: webinarId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    populateCommentsTable(response.data);
                } else {
                    $('#commentMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function () {
                $('#commentMessage').html('<div class="alert alert-danger">Error loading comments.</div>');
            }
        });
    }

    // Populate comments table
    function populateCommentsTable(comments) {
        const tbody = $('#commentsTable tbody');
        tbody.empty();
        if (comments.length === 0) {
            tbody.append('<tr><td colspan="4" class="text-center">No comments found.</td></tr>');
            return;
        }
        comments.forEach(function (comment) {
            const imgSrc = comment.profile_picture ? comment.profile_picture : 'assets/images/default.png';
            const row = $('<tr>');
            row.append('<td>' + comment.id + '</td>');
            row.append('<td>' + escapeHtml(comment.name) + '</td>');
            row.append('<td><img src="' + imgSrc + '" alt="Profile" style="width:50px; height:50px; object-fit:cover; border-radius:50%;"></td>');
row.append('<td>' + escapeHtml(comment.comment) + '</td>');
            row.append('<td>' +
                '<button class="btn btn-sm btn-danger delete-comment-btn" data-id="' + comment.id + '">Delete</button>' +
                '</td>');
            tbody.append(row);
        });

        // Attach event handlers for edit and delete buttons
        $('.edit-comment-btn').click(function () {
            const id = $(this).data('id');
            editComment(id);
        });
        $('.delete-comment-btn').click(function () {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this comment?')) {
                deleteComment(id);
            }
        });
    }

    // Clear comment form
    function clearCommentForm() {
        $('#commentId').val('');
        $('#commentName').val('');
        $('#commentMessage').val('');
        $('#commentRating').val('');
        $('#commentImage').val('');
        $('#commentMessage').html('');
    }

    // Submit comment form (create or update)
    function submitCommentForm() {
        const formData = new FormData($('#commentForm')[0]);
        const id = $('#commentId').val();
        const action = id ? 'update' : 'create';
        formData.append('action', action);
        if (!id) {
            formData.append('webinar_id', webinarId);
        } else {
            formData.append('id', id);
        }

        $.ajax({
            url: 'backend/webinar_comments.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#commentModal').modal('hide');
                    loadComments();
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Error submitting comment.');
            }
        });
    }

    // Edit comment - load data into form
    function editComment(id) {
        $.ajax({
            url: 'backend/webinar_comments.php',
            method: 'GET',
            data: { action: 'list', webinar_id: webinarId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const comment = response.data.find(c => c.id == id);
                    if (comment) {
                        $('#commentId').val(comment.id);
                        $('#commentName').val(comment.name);
                        $('#commentMessage').val(comment.comment);
                        $('#commentRating').val(comment.rating || '');
                        // Image cannot be set programmatically for file input
                        $('#commentModal').modal('show');
                    } else {
                        alert('Comment not found.');
                    }
                } else {
                    alert('Failed to load comment data.');
                }
            },
            error: function () {
                alert('Error loading comment data.');
            }
        });
    }

    // Delete comment
    function deleteComment(id) {
        $.ajax({
            url: 'backend/webinar_comments.php',
            method: 'POST',
            data: { action: 'delete', id: id },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    loadComments();
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Error deleting comment.');
            }
        });
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }
});
