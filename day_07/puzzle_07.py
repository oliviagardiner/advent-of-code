import sys
import itertools
sys.path.append('../day_02')
import puzzle_02

# https://adventofcode.com/2019/day/7
# Day 7: Amplification Circuit
# PART 1

li = puzzle_02.process_input('input_07')

# I decided to add another input to the original intcode processor to be able
# to handle both the phase setting and the input signal. I used the built-in
# permutations function to get every possible combination of the phase settings
# and iterate through the combinations, then take the highest result.

def run_amp(li, phase_setting, input_signal):
    li_copy = li.copy()
    output = puzzle_02.process_intcode(li_copy, phase_setting, input_signal)[1][-1]
    return output

def run_program(li):
    possible_phase_settings = [0, 1, 2, 3, 4]
    perm = itertools.permutations(possible_phase_settings, 5)

    outputs = []
    for p in perm:
        settings = list(p)
        input = 0
        for n in range(0, 5):
            input = run_amp(li, settings[n], input)
        outputs.append(input)

    return max(outputs)

# print(run_program(li))
# 567045

# PART 2
