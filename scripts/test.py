from opencc import OpenCC

cc = OpenCC('s2t')
print("簡體:\t%s" % "庄悰清 卫晓红")
line = cc.convert("庄悰清 卫晓红")
print("繁體:\t%s" % line)