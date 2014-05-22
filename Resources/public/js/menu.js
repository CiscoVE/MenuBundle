$(function()
{
    $( 'li.menu.sibling ul' ).each(function()
    {
        $( this ).children( 'li:first-child' ).children( 'a' ).addClass( 'first' );
        $( this ).children( 'li:last-child' ).children( 'a' ).addClass( 'last' );
    });
    $( '.navigation.main li.menu' ).hover(
        function()
        {
            $( 'ul', this ).fadeIn( 200 );
            $( 'a.main', this ).addClass( 'menuitem' );
        },
        function()
        {
            $( 'ul', this ).fadeOut( 300 );
            $( 'a.main', this ).removeClass( 'menuitem' );
        }
    );
});
