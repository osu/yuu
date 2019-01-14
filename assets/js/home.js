var tab_active = 0;

$(document).ready(() => {

    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });

    var truncate = function (elem, limit, after) {
        if (!elem || !limit || !after) return;
        if (elem.length < limit) return elem;
        return elem.trim().slice(0, limit) + after;
    };

    var upload = (file) => {
        var formData = new FormData();
        formData.append("upload[]", file);
        var uniqid = Date.now();
        var uniqid2 = Date.now() + uniqid;
        var uniqid3 = Date.now() + uniqid2 + uniqid;
        $(".uploadedfiles").append(`
        <li id="${uniqid3}">
            <p id="${uniqid}">${truncate(file.name, 20, "...")}</p>
            <progress id="${uniqid2}" class="progress is-primary"></progress>
        </li>        
        `);
        $.ajax({
            type: 'POST',
            url: '/api/upload.php',
            data: formData,
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', (e) => {
                        if (e.lengthComputable) {
                            var max = e.total;
                            var current = e.loaded;
                            $("#" + uniqid2).prop("max", max);
                            $("#" + uniqid2).prop("value", current);
                        }
                    }, false);
                }
                return myXhr;
            },
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data)
                try {
                    $("#" + uniqid).html(`
                    <a href="${data.items[0].url}">${data.items[0].url}</a>
                    `);
                } catch (error) {
                    $("#".uniqid3).html("");
                    toast({
                        type: "error",
                        title: "Unable to upload file"
                    });
                }
            },
            error: function (data) {
                console.log(data)
                $("#".uniqid3).html("");
                toast({
                    type: "error",
                    title: "Unable to upload file"
                });
            }
        });
    }

    $(".navbar-burger").click(function () {
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");
    });

    $("#upload").click(() => {
        $("#input").trigger("click");
    });

    $("form").submit((e) => {
        e.preventDefault();
    });

    $("#input").change((e) => {
        e.preventDefault();
        if ($("#input")[0].files.length > 0) {
            $(".box").css("display", "block");
            var files = e.target.files || e.dataTransfer.files;
            for (var i = 0, f; f = files[i]; i++) {
                upload(f);
            }
        }
    });

    $("#logo").click(() => {
        if ($("video").get(0).muted == true) {
            $("video").get(0).muted = false;
            $("video").get(0).volume = 0.01;
        } else {
            $("video").get(0).muted = true;
        }
    });

    $(document).on('dragenter', '#upload', function () {
        $(this).css('border', '3px dashed #5044a5');
        return false;
    });

    $(document).on('dragover', '#upload', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border', '3px dashed #5044a5');
        return false;
    });

    $(document).on('dragleave', '#upload', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css('border', 'none');
        return false;
    });

    $(document).on('drop', '#upload', function (e) {
        $(this).css('border', 'none');
        e.preventDefault();
        if (e.originalEvent.dataTransfer) {
            if (e.originalEvent.dataTransfer.files.length > 0) {
                var filesDrop = e.originalEvent.dataTransfer.files;
                for (var i = 0; i < filesDrop.length; i++) {
                    $(".box").css("display", "block");
                    upload(filesDrop[i]);
                }
            }
        }
    });

    var string = "";
    $(document).keydown((e) => {

        var key = e.originalEvent.key;

        string += key;

        if (string.toLowerCase().indexOf("faq") != -1) {
            window.location.replace("/faq.html");
        }

        if (string.toLowerCase().indexOf("contact") != -1) {
            window.location.replace("https://discord.gg/kUK9E7f");
        }

        if (string.toLowerCase().indexOf("home") != -1) {
            window.location.replace("/index.html");
        }

        if (string.toLowerCase().indexOf("tools") != -1) {
            window.location.replace("/tools.html");
        }

    });

});