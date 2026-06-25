root_pc = None
        third_pc = None
        fifth_pc = None
        for r in pcs:
            intervals = sorted([(p - r) % 12 for p in pcs])
            if intervals == [0, 3, 7] or intervals == [0, 4, 7]:
                root_pc = r
                third_pc = pcs[intervals.index(3 if 3 in intervals else 4)] # Wait, careful. intervals is sorted, not mapped back to pcs directly.
                # Better: find which pc gives interval 3 or 4, and which gives 7.
                for p in pcs:
                    if (p - r) % 12 == 3 or (p - r) % 12 == 4:
                        third_pc = p
                    elif (p - r) % 12 == 7:
                        fifth_pc = p
                break