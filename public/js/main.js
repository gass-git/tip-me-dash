// welcome page play button script //
$(document).ready(function(){
    const videoSrc = $("#player-1").attr("src");
    $(".video-play-btn, .video-popup").on("click", function(){
        if($(".video-popup").hasClass("open")){
            $(".video-popup").removeClass("open");
            $("#player-1").attr("src","");
        }
        else{
            $(".video-popup").addClass("open");
            if($("#player-1").attr("src")==''){
                $("#player-1").attr("src",videoSrc);
            }
        }
    });
});

// avatar change script (edit profile) //
const file = document.getElementById("avatar");
const preview_container = document.getElementById("change-avatar");
const current_avatar = preview_container.querySelector(".current-avatar");
const preview_new_avatar = preview_container.querySelector(".preview-new-avatar");

avatar.addEventListener("change", function(){
    const file = this.files[0];

    if(file){
        const reader = new FileReader();

        current_avatar.style.display = "none";
        preview_new_avatar.style.display = "block";

        reader.addEventListener("load", function(){
            console.log(this);
            preview_new_avatar.setAttribute("src",this.result);
        });

        reader.readAsDataURL(file);

    }else{
        current_avatar.style.display = null;
        preview_new_avatar.style.display = null;
        preview_new_avatar.setAttribute("src", "");
    }
});



