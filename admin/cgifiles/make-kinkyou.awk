#!/usr/bin/awk -f

BEGIN {
    FS = "\t"
}

{
    print "<article>"
    print "    <p class=\"honbun\">" $2 "</p>"
    print "    <p class=\"postdate\"><time>" $1 "</time></p>"
    print "</article>"
    print "<hr>"
}
