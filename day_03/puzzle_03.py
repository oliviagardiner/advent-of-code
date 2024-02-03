# https://adventofcode.com/2019/day/3
# Day 3: Crossed Wires
# PART 1

def process_input(filename):
    file = open(filename, 'r+')
    lines = file.readlines()
    file.close()

    input = []

    for line in lines:
        input.append(line.replace('\n', '').split(','))

    return input

wires = process_input('input_03')

# My approach was to turn the movement inputs into (x, y) coordinates, python
# handles tuples so I organized them into a list of tuples.

def get_all_coords(wire_movements):
    coords = []

    for movement in wire_movements:
        prev = coords[-1] if len(coords) > 0 else (0, 0)
        next = calculate_next_coord(movement, prev)
        coords.append(next)

    return coords

def calculate_next_coord(str, coord):
    dir = str[0].lower()
    val = int(str[1:])
    next_coord = list(coord)
    if (dir == 'l'):
        next_coord[0] = coord[0] - val
    elif (dir == 'r'):
        next_coord[0] = coord[0] + val
    elif (dir == 'd'):
        next_coord[1] = coord[1] - val
    elif (dir == 'u'):
        next_coord[1] = coord[1] + val
    else:
        raise Exception('Unknown direction: ', dir)
    return tuple(next_coord)

# We need to find out whether these wires intersect and we do that by trying to
# determine whether any 2 segments of these wires have a common point. Segments
# are defined by two pairs of coordinates and we take advantage of the fact that
# they are always either perfectly vertical or perfectly horizontal (grid).

def segment_intersect(a, b, c, d):
    x1, y1, x2, y2 = a[0], a[1], b[0], b[1]
    x3, y3, x4, y4 = c[0], c[1], d[0], d[1]

    if x1 == x2 and x3 == x4 and x1 != x3:
        return False # segments are horizontally parallel
    elif y1 == y2 and y3 == y4 and y1 != y3:
        return False #segments are vertically parallel
    elif x1 == x2 and x2 == x3 and x3 == x4:
        return False # segments are horizonatally collinear
    elif y1 == y2 and y2 == y3 and y3 == y4:
        return False # segments are vertically collinear
    else:
        xa = x1 if x1 == x2 else x3 # x = vertical segment x
        ya = y1 if y1 == y2 else y3 # y = horizontal segment y

        if does_segment_include_point(a, b, (xa, ya)) == True and does_segment_include_point(c, d, [xa, ya]):
            return tuple([xa, ya])
        else:
            return False

def does_segment_include_point(a, b, p):
    x1, y1, x2, y2, xa, ya = a[0], a[1], b[0], b[1], p[0], p[1]

    if xa > max(x1, x2) or xa < min(x1, x2):
        return False
    elif ya > max(y1, y2) or ya < min(y1, y2):
        return False
    else:
        return True

def get_intersections(coords1, coords2):
    points = [] # We need to compare every A wire segment to every B wire segment

    for n in range(0, len(coords1) - 1):
        for m in range(0, len(coords2) - 1):
            intersect = segment_intersect(coords1[n], coords1[n +1], coords2[m], coords2[m + 1])
            if (intersect != False):
                points.append(intersect)

    return points

def get_closest_intersection(wire1, wire2):
    coords1 = get_all_coords(wire1)
    coords2 = get_all_coords(wire2)

    points = get_intersections(coords1, coords2)
    sorted_points = sorted(list(map(lambda x: abs(x[0]) + abs(x[1]), points))) # We sort by the highest sum of the absolute value of the coordinates

    return sorted_points[0]

# print(get_closest_intersection(wires[0], wires[1]))
# 8015

# PART 2

def get_point_distance(wire, coords, point):
    end_segment_index = -1

    for n in range(0, len(coords) - 1):
        if does_segment_include_point(coords[n], coords[n + 1], point) == True:
            end_segment_index = n
            break
        else:
            continue

    if end_segment_index < 0:
        raise Exception('Wire does not appear to cross this point, something\'s wrong.')
    else:
        distance = 0

        for n in range(0, end_segment_index + 1): # We add up the values from the original list (easier than computing from the coordinates)
            val = int(wire[n][1:])
            distance += val

        # The last value won't be derived from the wire movement values but the intersection point coordinates
        last_coord = coords[n]

        if last_coord[0] == point[0]: # If x doesn't change, movement was on the y axis.
            distance += abs(last_coord[1] - point[1])
        elif last_coord[1] == point[1]: # If y doesn't change, movement was on the x axis.
            distance += abs(last_coord[0] - point[0])
        else:
            raise Exception('Point does not seem to be on the last segment, something\'s wrong.')

        return distance

def get_point_distance_all(wire, coords, points):
    distances = []

    for point in points:
        d = get_point_distance(wire, coords, point)
        distances.append(d)

    return distances

def find_shortest_distance(wire1, wire2):
    coords1 = get_all_coords(wire1)
    coords2 = get_all_coords(wire2)
    points = get_intersections(coords1, coords2)

    distances1 = get_point_distance_all(wire1, coords1, points)
    distances2 = get_point_distance_all(wire2, coords2, points)

    combined = list(map(lambda x, y: x + y, distances1, distances2))
    combined = sorted(combined)

    return combined[0]

# print(find_shortest_distance(wires[0], wires[1]))
# 163676
