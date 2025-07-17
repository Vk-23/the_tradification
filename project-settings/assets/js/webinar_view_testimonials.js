$(document).ready(function () {
    const webinarId = new URLSearchParams(window.location.search).get('id');
    if (!webinarId) {
        console.error('No webinar ID specified for testimonials.');
        return;
    }

    function loadTestimonials() {
        $.ajax({
            url: 'project-settings/backend//webinar_testimonial.php?action=list&webinar_id=' + webinarId,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    const tbody = $('#testimonialsTable tbody');
                    tbody.empty();
                    response.testimonials.forEach(function (testimonial) {
                        const row = $('<tr>');
                        row.append($('<td>').text(testimonial.id));
                        row.append($('<td>').text(testimonial.name));
                        const imgTd = $('<td>');
                        if (testimonial.image) {
                            imgTd.append('<img src="project-settings/backend//uploads/' + testimonial.image + '" alt="Testimonial Image" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">');
                        }
                        row.append(imgTd);
                        const actionsTd = $('<td>');
                        actionsTd.append('<button class="btn btn-sm btn-info editTestimonialBtn mr-2" data-id="' + testimonial.id + '" data-name="' + testimonial.name + '" data-image="' + testimonial.image + '">Edit</button>');
                        actionsTd.append('<button class="btn btn-sm btn-danger deleteTestimonialBtn" data-id="' + testimonial.id + '">Delete</button>');
                        row.append(actionsTd);
                        tbody.append(row);
                    });
                } else {
                    alert('Failed to load testimonials: ' + response.message);
                }
            },
            error: function () {
                alert('Error loading testimonials.');
            }
        });
    }

    loadTestimonials();

        $('#openTestimonialModalBtn').on('click', function () {
            $('#testimonialId').val('');
            $('#testimonialName').val('');
            $('#testimonialMessage').val('');
            $('#testimonialRating').val('');
            $('#testimonialImage').val('');
            $('#testimonialModalLabel').text('Add Testimonial');
            $('#saveTestimonialBtn').text('Add');
            $('#testimonialModal').modal('show');
        });

        $('#testimonialForm').on('submit', function (e) {
            e.preventDefault();
            const id = $('#testimonialId').val();
            const name = $('#testimonialName').val().trim();
            const message = $('#testimonialMessage').val().trim();
            const rating = $('#testimonialRating').val();
            const imageFile = $('#testimonialImage')[0].files[0];

            if (!name || !message || !rating) {
                alert('Please fill all required fields for the testimonial.');
                return;
            }

            const formData = new FormData();
            formData.append('webinar_id', webinarId);
            formData.append('name', name);
            formData.append('message', message);
            formData.append('rating', rating);
            if (imageFile) {
                formData.append('image', imageFile);
            }
            if (id) {
                formData.append('id', id);
            }

            const action = id ? 'update' : 'add';

            $.ajax({
                url: 'project-settings/backend//webinar_testimonial.php?action=' + action,
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#testimonialModal').modal('hide');
                        loadTestimonials();
                        alert(response.message);
                    } else {
                        alert('Failed to save testimonial: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error saving testimonial.');
                }
            });
        });

    $(document).on('click', '.editTestimonialBtn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        // Image file input cannot be set programmatically for security reasons
        $('#testimonialId').val(id);
        $('#testimonialName').val(name);
        $('#testimonialImage').val('');
        $('#testimonialModalLabel').text('Update Testimonial');
        $('#saveTestimonialBtn').text('Update');
        $('#testimonialModal').modal('show');
    });

    $(document).on('click', '.deleteTestimonialBtn', function () {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this testimonial?')) {
            $.ajax({
                url: 'project-settings/backend//webinar_testimonial.php?action=delete',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ id: id, webinar_id: webinarId }),
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        loadTestimonials();
                        alert(response.message);
                    } else {
                        alert('Failed to delete testimonial: ' + response.message);
                    }
                },
                error: function () {
                    alert('Error deleting testimonial.');
                }
            });
        }
    });
});
