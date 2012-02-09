#!/usr/bin/env watchr

# Watch for changes to any LESS files and recompile the phills.less file
watch( 'public/lib/.*\.less' ) {|md|
	system("lessc public/lib/womble.less > public/css/womble.css")
	system("lessc public/lib/womble.less > public/css/womble.min.css --compress")
	puts Time.now.strftime("%H:%M:%S") + " Compiled womble.less"
}
