#!/usr/bin/env python3

import unitypack
import sys
import hashlib
import json

filename = sys.argv[1]

def file_as_bytes(file):
    with file:
        return file.read()

with open(filename, "rb") as f:
    bundle = unitypack.load(f)
    for asset in bundle.assets:
        for id, object in asset.objects.items():
            if object.type == "SaberDescriptor":
                fileType = "saber"
                data = object.read()
                objectName = data["SaberName"]
                objectAuthor = data["AuthorName"]
            elif object.type == "AvatarDescriptor":
                fileType = "avatar"
                data = object.read()
                objectName = data["AvatarName"]
                objectAuthor = data["AuthorName"]
            elif object.type == "CustomPlatform":
                fileType = "platform"
                data = object.read()
                objectName = data["platName"]
                objectAuthor = data["platAuthor"]

hashsum = hashlib.md5(file_as_bytes(open(filename, 'rb'))).hexdigest()

print(fileType)
print(objectName)
print(objectAuthor)
print(hashsum)
