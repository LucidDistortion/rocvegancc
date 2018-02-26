function hvr(dom, action)
{
    if (action == 'in')
    {
        $(dom).find("[col=g]").css("display", "none");
        $(dom).find("[col=b]").css("display", "inline-block");
    }

    else
    {
        $(dom).find("[col=b]").css("display", "none");
        $(dom).find("[col=g]").css("display", "inline-block");
    }
}