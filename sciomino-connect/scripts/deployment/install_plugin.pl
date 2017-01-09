#!/usr/bin/perl -w

use strict;

#
# commando's voor install
#
# cd into working dir (DIRECTORY)
# export voor install 
# - svn export file:///var/svn/REPOSITORY/trunk NAME
# - set versie van svn
#

my $name;
my $directory;
my $repository;
my $branch;

if ($#ARGV + 1 != 4) {
    	print "Usage: install_plugin.pl [NAME|plugin/sciomino] [DIRECTORY|sciomino-www-build] [REPOSITORY|sciomino-www] [BRANCHE|1.2.o|trunk] (DIRECTORY is the place where REPOSITORY wil be installed with NAME)\n";
	exit;
}
else {
	$name = "$ARGV[0]";
	$directory = "$ARGV[1]";
	$repository = "$ARGV[2]";
	$branch = "$ARGV[3]";
}

#
# init
#

# revision wil be read from repository
my $revision = "";

my $working_dir = "/var/www-virtual/".$directory;
my $repository_dir  = "file:///var/svn/".$repository."/trunk";
if ($branch ne "trunk") {
	$repository_dir = "file:///var/svn/".$repository."/branches/".$branch;
}

#
# 1. change to working directory
#
chdir "$working_dir";

if ($directory ne "") {

	print "installing repository '$repository'...\n";

	#
	# 1. export
	#
	system("/usr/bin/svn export $repository_dir $name");
	system("/usr/bin/svnversion > version/".$repository."-revision");

}

