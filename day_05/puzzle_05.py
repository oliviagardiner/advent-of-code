import sys
sys.path.append('../day_02')
import puzzle_02

# https://adventofcode.com/2019/day/5
# Day 5: Sunny with a Chance of Asteroids
# PART 1

li = puzzle_02.process_input('input_05')

# My Day 2 code was not suitable to handle the changes, but I still
# decided to write the new methods in the Day 2 files because adding
# extra functionality meant that the original one still had to work
# with the modifications!

aircon_unit_id = 1

test = puzzle_02.process_intcode(li, aircon_unit_id)
# print(test[1][-1])
# 9654885

# Part 2

system_id = 5

test2 = puzzle_02.process_intcode(li, system_id)
# print(test2[1][0])
# 7079459
