// let $ = jQuery;
$(function(){
    $("input[type=file].custom-file-input").change(function (e){$(this).next('.custom-file-label').text(e.target.files[0].name);})



    // Set Interval

    // $.ajax('/messages/check').then((result) => console.log(result));

    // setInterval(myCallback, 10000);

    // function myCallback()
    // {
    //     var badge = $('.notificationBadge');

    //     $.ajax('/messages/check').then((result) => (badge.text(result.count)));

    //     // badge.css.top = "10px";
    //     // badge.css.right = "168px";
    //     // badge.css.fontSize = ".7em";
    //     // badge.css.backgroundColor = "red";
    //     // badge.css.color = "white";
    //     // badge.css.width = "18px";
    //     // badge.css.height = "18px";
    //     // badge.css.textAlign = "center";
    //     // badge.css.lineHeight = "18px";
    //     // badge.css.borderRadius = "50%";

    // }


    // $('.notificationBadge').text(1);

});
