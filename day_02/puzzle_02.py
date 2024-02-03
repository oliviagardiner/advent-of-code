# https://adventofcode.com/2019/day/2
# Day 2: 1202 Program Alarm
# PART 1

def process_input(filename):
    file = open(filename, 'r+')
    line = file.readline()
    file.close()

    input = list(map(int, line.split(',')))

    return input

def opcode1(list, param1, param2, target):
    list[target] = param1 + param2
    return list

def opcode2(list, param1, param2, target):
    list[target] = param1 * param2
    return list

# Day 5 functions:

def opcode3(list, val, target):
    list[target] = val
    return list

def opcode7(list, param1, param2, target):
    list[target] = 1 if param1 < param2 else 0
    return list

def opcode8(list, param1, param2, target):
    list[target] = 1 if param1 == param2 else 0
    return list

def interpret_mode(list, index, mode):
    if mode == 0:
        # We are in position mode
        return list[index]
    elif mode == 1:
        # We are in immediate mode
        return index
    else:
        raise ValueError('Invalid mode:', mode)

def compute_parameter_count(opcode):
    if opcode in [1, 2, 7, 8]:
        return 4
    elif opcode in [3, 4]:
        return 2
    elif opcode in [5, 6]:
        return 3
    elif opcode == 99:
        return 1
    else:
        raise ValueError('Unknown opcode, something went wrong!')

# The original Day 2 solution:

def process_intcode_old(list):
    for pos in range(0, len(list), 4):
        pos1_index = list[pos + 1]
        pos2_index = list[pos + 2]
        target_index = list[pos + 3]

        if (list[pos] not in [1, 2, 99]):
            raise ValueError('Unknown opcode, something went wrong!')
        elif list[pos] == 1:
            list = opcode1(list, pos1_index, pos2_index, target_index)
        elif list[pos] == 2:
            list = opcode2(list, pos1_index, pos2_index, target_index)
        else:
            break

    return list

# Function updated for Day 5, still works for the Day 2 input:

def process_intcode(list, inp = None, inp2 = None):
    pos = 0

    outputs = []
    inp_used = 0

    while pos < len(list) - 2:
        if list[pos] < 100:
            # We are in position mode
            opcode = list[pos]
            param1_mode, param2_mode = 0, 0
        else:
            # We are in parameter mode
            opcode = int(str(list[pos])[-2:])
            param1_mode = int(str(list[pos])[-3]) if list[pos] >= 100 else 0
            param2_mode = int(str(list[pos])[-4]) if list[pos] >= 1000 else 0
            param3_mode = int(str(list[pos])[-5]) if list[pos] >= 10000 else 0

            if (param3_mode != 0):
                raise Exception('Parameter 3 should ALWAYS be in position mode!')

        if opcode in [1, 2, 5, 6, 7, 8]:
            param1, param2 = list[interpret_mode(list, pos + 1, param1_mode)], list[interpret_mode(list, pos + 2, param2_mode)]
            target_index = list[pos + 3]
        else:
            target_index = interpret_mode(list, pos + 1, param1_mode)

        pos += compute_parameter_count(opcode)

        if (opcode not in [1, 2, 3, 4, 5, 6, 7, 8, 99]):
            raise ValueError('Unknown opcode, something went wrong!')
        elif opcode == 1:
            list = opcode1(list, param1, param2, target_index)
        elif opcode == 2:
            list = opcode2(list, param1, param2, target_index)
        elif opcode == 3 and inp != None:
            if inp2 != None and inp_used == 1:
                inp_mod = inp2
            else:
                inp_mod = inp
            list = opcode3(list, inp_mod, target_index)
            inp_used = 1
        elif opcode == 4 and inp != None:
            outputs.append(list[target_index])
        elif opcode in [5, 6] and inp != None:
            if opcode == 5 and param1 != 0:
                pos = param2
            elif opcode == 6 and param1 == 0:
                pos = param2
        elif opcode == 7 and inp != None:
            list = opcode7(list, param1, param2, target_index)
        elif opcode == 8 and inp != None:
            list = opcode8(list, param1, param2, target_index)
        else:
            break

    return [list[0], outputs]

def replace_instruction_parameters(inp1, inp2):
    list = process_input('input_02')
    list[1] = inp1
    list[2] = inp2
    return list

# print(process_intcode(replace_instruction_parameters(12, 2))[0])
# 2782414

# PART 2

def find_target_output(target):
    for noun in range(0, 100):
        for verb in range (0, 100):
            mod_list = replace_instruction_parameters(noun, verb)
            mod_list = process_intcode(mod_list)
            if (mod_list[0] == target):
                break
        else:
            continue
        break

    if (mod_list[0] != target):
        raise Exception('Could not find a result at', verb, ' ', noun, '.')
    else:
        return 100 * noun + verb

# after reverse engineering the output to optimize runtime (down to 0.02s from 1.5s)

def smart_find_target_output(target):
    for noun in range(0, 100):
        mod_list = replace_instruction_parameters(noun, 0)
        mod_list = process_intcode(mod_list)

        if (noun == 0 and mod_list[0] > target):
            raise Exception('Target value is too low: ', target)
            break
        elif (target > mod_list[0] and target < mod_list[0] + 99):
            for verb in range(0, 99):
                mod_list = replace_instruction_parameters(noun, verb)
                mod_list = process_intcode(mod_list)
                if (mod_list[0] == target):
                    break
            else:
                continue
            break
        else:
            continue

    if (mod_list[0] != target):
        raise Exception('Could not find a result at', verb, ' ', noun, '.')
    else:
        return 100 * noun + verb

# print(find_target_output(19690720))
# print(smart_find_target_output(19690720))
# 9820
