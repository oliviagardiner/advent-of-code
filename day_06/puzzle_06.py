import json

# https://adventofcode.com/2019/day/6
# Day 6: Universal Orbit Map
# PART 1

def process_input(filename):
    file = open(filename, 'r+')
    lines = file.readlines()
    file.close()

    list = []
    set1 = set()
    set2 = set()

    for line in lines:
        pair = line.replace('\n', '').split(')')
        list.append(tuple(pair))
        set1.add(pair[0])
        set2.add(pair[1])

    return list, set1, set2

# My idea was to reassemble the tuples into a dictionary. I thought
# that we could recursively count the dictionary keys and add them up
# to get the total number of direct and indirect orbits.

g_keys = 0

def count_dict(d):
    global g_keys
    keys = 0

    for k, v in d.items():
        keys += 1
        if isinstance(v, dict) and len(v) > 0:
            k1 = count_dict(v)
            keys += k1

    g_keys += keys
    return keys

k_path = []

def find_key(key, d):
    global k_path
    if key in d:
        return True
    else:
        for k, v in d.items():
            if k == key:
                return True
            elif isinstance(v, dict):
                exists = find_key(key, v)
                if (exists == True):
                    k_path.append(k)
                    return True
            else:
                return False

def find_center(orbited, orbiting):
    return orbited - orbiting

def find_orbits(li, d, key):
    d = d.setdefault(key, {})
    orbiting_bodies = [tup[1] for tup in li if key == tup[0]]
    for body in orbiting_bodies:
        d[body] = {}
        find_orbits(li, d, body)
    return d

input, orbited, orbiting = process_input('input_06')
center = list(find_center(orbited, orbiting))[0]
orbit_map = find_orbits(input, {}, center)
# count_dict(orbit_map)
# print(g_keys)
# 122782

# To save our results as a json:

# with open('orbit_map.json', 'w') as fp:
#     json.dump(orbit_map, fp, indent=4)

# PART 2

# My idea was to find the path to 'YOU' and to 'SAN' individually, and
# compute the orbit transfers from the path differences.

def calc_two_paths(d, start, end):
    global k_path
    k_path = []
    find_key(start, d)
    path_start = k_path.copy()
    k_path = []
    find_key(end, d)
    path_end = k_path.copy()

    return path_start, path_end

path1, path2 = calc_two_paths(orbit_map, 'YOU', 'SAN')
path1.reverse()
path2.reverse()

def calc_orbital_transfers(path1, path2):
    max = min(len(path1), len(path2)) - 1
    div_index = 0
    for n in range(0, max):
        if path1[n] != path2[n]:
            div_index = n - 1
            break

    return len(path1[(div_index + 1):]) + len(path2[(div_index + 1):])

distance = calc_orbital_transfers(path1, path2)
# print(distance)
# 271
