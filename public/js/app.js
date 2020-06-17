$(function(){


    $("input[type=file].custom-file-input").change(function (e){$(this).next('.custom-file-label').text(e.target.files[0].name);})


    setInterval(myCallback, 10000);

    function myCallback()
    {
        var badge = $('.badge');

        $.ajax('/messages/check').then((result) => {
            if(result.count === 0)
            { 
                badge.text = "";
            }
            else
            {
                badge.text(result.count)
            }
        });
    }

    myCallback();

});
