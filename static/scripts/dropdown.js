//This function uses variable arguments
//The first argument is the panel that should be shown
//All arguments after that are the panels that should not be shown anymore
function slide() {
    for (var i = 1; i < arguments.length; i++) {
        $(document.getElementById(arguments[i])).slideUp();
    }
    $(document.getElementById(arguments[0])).slideToggle();
}
