jQuery(document).ready(function($) {
    $("#add-dialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Add Redirect": function() {
                $("#add-form").submit();
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }
    });

    $("#edit-dialog").dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Save Changes": function() {
                $("#edit-form").submit();
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        }
    });

    $("#add-redirect").click(function() {
        $("#add-dialog").dialog("open");
    });

    $(".edit-redirect").click(function() {
        var id = $(this).data('id');
        var source_url = $(this).data('source_url');
        var target_url = $(this).data('target_url');
        var redirect_type = $(this).data('redirect_type');
        var status = $(this).data('status');

        $("#edit-id").val(id);
        $("#edit-source-url").val(source_url);
        $("#edit-target-url").val(target_url);
        $("#edit-redirect-type").val(redirect_type);
        $("#edit-status").prop('checked', status == 1);

        $("#edit-dialog").dialog("open");
    });
});
