import re

# https://adventofcode.com/2019/day/4
# Day 4: Secure Container
# PART 1

pass_range = [124075, 580769]

def validate_type(phrase):
    return True if type(phrase) is int else False

def validate_length(phrase):
    return True if len(str(phrase)) == 6 else False

def validate_increasing_digits(phrase):
    phrase = str(phrase)
    for n in range(1, len(phrase)):
        if (phrase[n] >= phrase[n - 1]):
            continue
        else:
            return False
    return True

def validate_double_digits(phrase):
    phrase = str(phrase)
    for n in range(1, len(phrase)):
        if (phrase[n] == phrase[n - 1]):
            return True
        else:
            continue
    return False

def is_phrase_valid(phrase):
    return validate_type(phrase) == True and validate_length(phrase) == True and validate_increasing_digits(phrase) == True and validate_double_digits(phrase) == True

# Kind of a brute force method?
# Idea to revisit: if a phrase does not pass the increasing digits validation, 
# we find the index of the failure and keep incrementing it until it passes,
# therefore 124075 fails on index 3, next we check 1241xx, then 1242xx.
# Check if this would improve runtime significantly with large ranges.

def test_range(list, validator):
    counter = 0

    for n in range(list[0], list[1]):
        if validator(n):
            counter += 1
    
    return counter

# print(test_range(pass_range, is_phrase_valid))
# 2150

# PART 2

def regex_validate(phrase):
    phrase = str(phrase)
    for n in range(0, len(phrase) - 1):
        reg2 = re.compile(re.escape(phrase[n]) + '{2}?')
        reg3 = re.compile(re.escape(phrase[n]) + '{3}?')
        if re.search(reg2, phrase) and re.search(reg3, phrase) == None:
            return True
        else:
            continue
    return False

def is_phrase_still_valid(phrase):
    return validate_type(phrase) == True and validate_length(phrase) == True and validate_increasing_digits(phrase) == True and regex_validate(phrase) == True

# print(test_range(pass_range, is_phrase_still_valid))
# 1462
