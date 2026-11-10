#!/usr/bin/env python3
"""
ElNemer OAI-PMH 2.0, MARC21 & W3C Linked Open Data (RDF/Turtle) University Library Connector — Command-Line Verification & Metadata Client
Author: Eng. Mahmoud Bek ElNemer (ORCID: 0009-0008-7553-2167)
License: MIT
"""
import sys, json, urllib.request

UPSTREAM = "https://million.elnemer-ai.com"

def inspect_volume(vol_id: int = 6000001):
    url = f"{UPSTREAM}/knowledge-graph?id={vol_id}&format=jsonld"
    req = urllib.request.Request(url, headers={"User-Agent": "ElNemer-CLI/1.0"})
    with urllib.request.urlopen(req, timeout=12) as resp:
        data = json.loads(resp.read().decode("utf-8"))
    print(json.dumps(data, indent=2, ensure_ascii=False))

if __name__ == "__main__":
    vid = int(sys.argv[1]) if len(sys.argv) > 1 else 6000001
    inspect_volume(vid)
