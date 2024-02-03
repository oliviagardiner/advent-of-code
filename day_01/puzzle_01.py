import math

# https://adventofcode.com/2019/day/1
# Day 1: The Tyranny of the Rocket Equation
# PART 1

def process_input(filename):
    file = open(filename, 'r+')
    lines = file.readlines()
    file.close()

    list = []

    for line in lines:
        list.append(int(line.split('/')[0]))

    return list

list = process_input('input_01')

def fuel_counter_upper(n):
    return math.floor(n / 3) - 2

def calculate_total_req(list, func):
    total = 0

    for i in list:
        total += func(i)

    return total

sum1 = calculate_total_req(list, fuel_counter_upper)

# print(sum1)
# 3511949

# PART 2

def calculate_module_req(n):
    req = 0

    while n > 0:
        n = fuel_counter_upper(n)
        if n > 0:
            req += n

    return req

sum2 = calculate_total_req(list, calculate_module_req)

# print(sum2)
# 5265045
