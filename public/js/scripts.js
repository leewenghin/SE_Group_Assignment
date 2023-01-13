// Side Navigation Bar
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    if(window.innerWidth > 992){
        document.getElementById("main").style.marginLeft = "250px";
    }
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginLeft= "0";
}

function pupUpCommonTitleModel() {
    var group_checkbox = document.getElementsByClassName("group_checkbox");

    var total_checked = 0;

    for(let i = 0; i<group_checkbox.length; i++){
        total_checked += group_checkbox[i].checked ? 1 : 0;
    }

    if (total_checked> 0){
        $("#pupUpCommonTitleModelBtn").click();
    }else{
        $("#noticeToCheckAtLeastOneModelBtn").click();
    }
}

function openCommonTitle() {
    $("#form").attr("action", $("#create_new_verified_complaint").val());
    $("#commonTitle").attr("required", true);
    $("#group_existing").attr("required", false);
    $("#add-common-title").show();
    $("#add-to-existing").hide();
    pupUpCommonTitleModel();
}

function openGroupToExisting() {
    $("#form").attr('action', $("#add_complaint_to_existing").val());
    $("#commonTitle").attr("required", false);
    $("#group_existing").attr("required", true);
    $("#add-common-title").hide();
    $("#add-to-existing").show();
    pupUpCommonTitleModel();
}

$(document).ready(function(){
    $("#status_filter").change(function(){
        $("#search_filter_form").submit();
    });

    $("#student_upload_image_video").change(function(){
        let file = $(this)[0].files[0];

        img_ext = ['image/png', 'image/jpg', 'image/jpeg'];

        if (img_ext.includes(file.type)){
            $("#fileUploadWarningModelBtn").click();
        }
    });

    $("#executive_upload_file_evidence").change(function(){
        let file = $(this)[0].files[0];

        img_ext = ['image/png', 'image/jpg', 'image/jpeg'];

        if (img_ext.includes(file.type)){
            $("#fileUploadWarningModelBtn").click();
        }
    });

    $("input[type=radio][name=action]").click(function(){
        // console.log(this.value)
        if(this.value == "0"){
            // console.log("true")
            $("label[for=file_evidence], #executive_upload_file_evidence").removeClass("d-none").addClass("d-flex");
            $("#declineTaskRecivedModelBtn").click();
        }else{
            // console.log("false")
            $("label[for=file_evidence], #executive_upload_file_evidence").removeClass("d-fles").addClass("d-none");
        }
    });

    $(".is-need-department").click(function () {
        var action_value = $(this).val();
        if (action_value == "1") {
            $("#select_department").attr("required", true);
        }
        else {
            $("#select_department").attr("required", false);
        }
    });
});
