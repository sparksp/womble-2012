#!/usr/bin/env watchr

def compile_less
	system("lessc public/lib/womble.less > public/css/womble.css")
	system("lessc public/lib/womble.less > public/css/womble.min.css --compress")
	puts Time.now.strftime("%H:%M:%S") + " Compiled womble.less"
end



# Watch for changes to any LESS files and recompile the phills.less file
watch( 'public/lib/.*\.less' ) {|md|
	compile_less
}
compile_less # Run this when we launch too