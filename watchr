#!/usr/bin/env ruby1.9
def phpunit file
    puts file
    system("phpunit #{file}") if File.exists?(file)
end

watch '^tests/.*Test\.php' do |match|
  phpunit match[0]
end

watch '^lib/.*\.php' do |match|
  phpunit match[0]
end


# http://growl.info/extras.php#growlnotify

# def phpunit file
#   if File.exists? file
#     cmd = "phpunit #{file} 2>&1" # redirect stderr to stdout
#     say "About to run `#{cmd}`"
#     _, out, _ = Open3.popen3(cmd) # care only about stdout
# 
#     previous = last = nil
# 
#     until out.eof?
#       previous = last # remember last two lines of the PHPUnit output
#       puts last = out.gets
#     end
# 
#     file_name = File.basename(file)
#     image, summary, message = case
#     when last =~ /\AOK/ # PHPUnit is green
#       ['dialog-ok', file_name, last.gsub('OK (', '').gsub(')', '')]
#     when previous =~ /\AOK, but incomplete or skipped tests/ # PHPUnit is yellow
#       ['dialog-question', file_name, last]
#     when last =~ /\APHP/ # PHP Fatal error, PHPUnit process crashed
#       ['dialog-error', 'Fatal error!', last]
#     else # PHPUnit is red
#       ['dialog-warning', previous, last]
#     end
# 
#     # `--hint=string:x-canonical-private-synchronous:` is a workaround for `-t`
#     `notify-send  --hint=string:x-canonical-private-synchronous: -i #{image} "#{summary}" "#{message}"`
#     say "waiting..."
#   end
# end
