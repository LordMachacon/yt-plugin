var start_player = function(li){
		attrib = (li.getAttribute('yt_video_link'));
		var player = document.getElementsByClassName('yt-player-container')[0].children[0];

		player.src="https://youtube.com/embed/"+attrib+"?autoplay=1";	
}