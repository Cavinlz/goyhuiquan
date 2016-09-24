/*

*/

!function(){

    var ifr = document.createElement("iframe");
    ifr.frameBorder = '0';
    ifr.width = '100%';
    ifr.src = 'http://localhost/fanyi/api?cmd=gui&seccode='+$('#translateDiv').data('code');
    $('#translateDiv').append(ifr);
    
    window.title = $('h2').text();
    window.id = 1;
    window.content = $('#content').text();
    window.resetIframe = function(){ //调整iframe高度
        var _h = $('#translateDiv iframe')[0]
            .contentWindow.document.getElementsByTagName("body")[0].clientHeight;
        $('#translateDiv').height(_h);
        $('#translateDiv iframe').height(_h);
    };
}()