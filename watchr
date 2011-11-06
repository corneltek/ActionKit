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
