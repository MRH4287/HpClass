function vMenu( tableCellRef, hoverFlag, navStyle ) {
	if ( hoverFlag ) {
		switch ( navStyle ) {
			case 1:
				tableCellRef.style.backgroundColor = selectColor;
				tableCellRef.style.Color = '#000000';
				tableCellRef.style.height = '14';							
				break;
			default:
				if ( document.getElementsByTagName ) {
					tableCellRef.getElementsByTagName( 'a' )[0].style.color = '#c00';
				}
		}
	} else {
		switch ( navStyle ) {
			case 1:
				tableCellRef.style.backgroundColor = unselectColor;
				break;
			default:
				if ( document.getElementsByTagName ) {
					tableCellRef.getElementsByTagName( 'a' )[0].style.color = '#000';
				}
		}
	}
}