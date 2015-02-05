#!/usr/bin/env python

from __future__ import print_function

import os
import stat
import grp
import pwd
import sys

filename = sys.argv[1]
if not os.path.exists(filename):
	print ('{"error":"no such path"}')
	sys.exit(0)

details = os.stat(filename)
user = pwd.getpwuid(details.st_uid).pw_name
group = grp.getgrgid(details.st_gid).gr_name
mode = stat.S_IMODE(details.st_mode)
if os.path.isdir(filename):
	filetype="dir"
else:
	filetype="file"

print ('{"path":"%s","type": "%s","mode": %s,"uid": %s,"gid":%s,"user":"%s","group":"%s","size":%s,"ctime":%s,"atime":%s,"mtime": %s}' % ( os.path.abspath(filename), filetype, mode, details.st_uid, details.st_gid, user, group, details.st_size, details.st_ctime, details.st_atime, details.st_mtime))