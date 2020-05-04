#!/usr/bin/env python3

import unitypack
import sys
import hashlib
import json

filename = sys.argv[1]

shouldstop = 0

def file_as_bytes(file):
    with file:
        return file.read()
try:
    with open(filename, "rb") as f:
        bundle = unitypack.load(f)
        for asset in bundle.assets:
            for id, object in asset.objects.items():
                if object.type == "SaberDescriptor":
                    fileType = "saber"
                    data = object.read()
                    objectName = data["SaberName"]
                    objectAuthor = data["AuthorName"]
                    shouldstop = 1
                elif object.type == "AvatarDescriptor":
                    fileType = "avatar"
                    data = object.read()
                    objectName = data["AvatarName"]
                    objectAuthor = data["AuthorName"]
                    shouldstop = 1
                elif object.type == "CustomPlatform":
                    fileType = "platform"
                    data = object.read()
                    objectName = data["platName"]
                    objectAuthor = data["platAuthor"]
                    shouldstop = 1
                elif object.type == "NoteDescriptor":
                    fileType = "bloq"
                    data = object.read()
                    objectName = data["NoteName"]
                    objectAuthor = data["AuthorName"]
                    shouldstop = 1
                if shouldstop == 1:
                    break
            if shouldstop == 1:
                break
except ValueError:
    pass

hashsum = hashlib.md5(file_as_bytes(open(filename, 'rb'))).hexdigest()

print(fileType)
print(objectName)
print(objectAuthor)
print(hashsum)