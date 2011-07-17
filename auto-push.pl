 use File::ChangeNotify;

my $watcher =
    File::ChangeNotify->instantiate_watcher
        ( directories => [ '.git/refs/heads' ],
            # filter      => qr/\.(?:pm|conf|yml)$/,
        );
# blocking
while ( my @events = $watcher->wait_for_events() ) { 
    use Data::Dumper; warn Dumper( \@events );


    for my $e ( @events ) {
        if( $e->type eq 'modify' ) {
            if( $e->path =~ /(\w+)$/ ) {
                qx(git push origin $1);
            }
        }
    }


}
