function unloadMess()
{
	return 'All temporary Items, Exp, and Gold will be lost.'
} 
function pageLoaded() 
{ 
	window.onbeforeunload = unloadMess;
}