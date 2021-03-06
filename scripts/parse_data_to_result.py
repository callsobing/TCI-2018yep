import xlrd
import sys

round = sys.argv[1]

employee_id_mapping = {}
group_employee = {}

employee_group_fh = open("data/employee_groups_0118.txt", encoding="utf-8")
for line in employee_group_fh:
    line = line.rstrip()
    splitted = line.split("\t")
    employee_id_mapping[splitted[4]] = splitted[2]
employee_group_fh.close()

employee_group_fh = open("data/employee_groups_0118.txt", encoding="utf-8")
for line in employee_group_fh:
    line = line.rstrip()
    splitted = line.split("\t")
    team = splitted[1] + "-" + employee_id_mapping[splitted[0]]
    if team not in group_employee:
        group_employee[team] = []
    group_employee[team].append(splitted[4])


customer_info = {}
# 讀入試題統計
# 题号	题目	应得分	实得分	正确率	满分人数	零分人数	部分得分人数
# wb = xlrd.open_workbook("../data/20181227考试测试_云课堂(试题统计)_Min.Yue_20190107161736 (1).xlsx", on_demand=True)
wb = xlrd.open_workbook("data/test_stats.xlsx", on_demand=True)

sheet = wb.sheet_by_name("Sheet0")
nrows = sheet.nrows
full_score = 0
scored_points = 0
prob_score_pair = {}

for i in range(1, nrows):
    row_data = sheet.row_values(i)
    full_score += float(row_data[2])
    scored_points += float(row_data[3])
    prob_score_pair[row_data[1]] = "%.1f" % ((scored_points / full_score) * 100)

if round == "2":
    wb = xlrd.open_workbook("data/test_stats2.xlsx", on_demand=True)

    sheet = wb.sheet_by_name("Sheet0")
    nrows = sheet.nrows

    for i in range(1, nrows):
        row_data = sheet.row_values(i)
        full_score += float(row_data[2])
        scored_points += float(row_data[3])
        if row_data[1] in prob_score_pair:
            prob_score_pair[row_data[1]] = "%.1f" % ((scored_points / full_score) * 100)

prob_score_pair_sorted = sorted(prob_score_pair.items(), key=lambda kv: kv[1])

count = 0

for pair in prob_score_pair_sorted:
    if count < 6:
        count += 1
        output_fh = open("data/output_question%s.txt" % str(count), "w", encoding="utf-8")
        output_fh.write("%s\t%s\n" % (pair[0], str(pair[1])))
        output_fh.close()
    continue

total_avg = scored_points/full_score
total_avg_fh = open("data/output_total_avg.txt", "w", encoding="utf-8")
total_avg_fh.write("%.2f" % (total_avg * 100))
total_avg_fh.close()

# 讀入考試結果
# 排名	姓名	账号	手机号	部门	得分	是否通过	交卷时间	考试用时（分钟）
# wb = xlrd.open_workbook("../data/20181227考试测试_云课堂(考试结果)_Min.Yue_20190107161730 (1).xlsx", on_demand=True)
wb = xlrd.open_workbook("data/test_result.xlsx", on_demand=True)

sheet = wb.sheet_by_name("正考结果")
top10_employee = {}
employee_score_dict = {}

for i in range(1, sheet.nrows):
    row_data = sheet.row_values(i)
    employee_name = row_data[2]
    if row_data[5] == "":
        row_data[5] = 0.0
    employee_score = float(row_data[5])
    if employee_name not in employee_score_dict:
        employee_score_dict[employee_name] = employee_score
        employee_id = row_data[2]
        top10_employee[employee_id] = employee_score
    elif employee_score_dict[employee_name] < employee_score:
        employee_score_dict[employee_name] = employee_score
        employee_id = row_data[2]
        top10_employee[employee_id] = employee_score


top10_employee_total = {}
if round == "2":
    wb = xlrd.open_workbook("data/test_result2.xlsx", on_demand=True)

    sheet = wb.sheet_by_name("正考结果")

    for i in range(1, sheet.nrows):
        row_data = sheet.row_values(i)
        employee_name = row_data[2]
        if row_data[5] == "":
            row_data[5] = 0.0
        employee_score = float(row_data[5])
        if employee_name in employee_score_dict:
            employee_score_dict[employee_name] = (employee_score_dict[employee_name] + employee_score) / 2
        else:
            employee_score_dict[employee_name] = employee_score / 2
        employee_id = row_data[2]
        if employee_id in top10_employee:
            top10_employee[employee_id] = (top10_employee[employee_id] + employee_score) / 2
        else:
            top10_employee[employee_id] = employee_score / 2


top10_employee_fh = open("data/output_top10_employee.txt", "w", encoding="utf-8")
sorted_by_value = sorted(top10_employee.items(), key=lambda kv: kv[1], reverse=True)
count = 0
for kv in sorted_by_value:
    if kv[0] not in employee_id_mapping:
        continue
    if count < 10:
        count += 1
        top10_employee_fh.write("%s\t%s\n" % (employee_id_mapping[kv[0]], str(kv[1])))
top10_employee_fh.close()


last10_employee_fh = open("data/output_last10_employee.txt", "w", encoding="utf-8")
sorted_by_value = sorted(top10_employee.items(), key=lambda kv: kv[1])
count = 0
for kv in sorted_by_value:
    if kv[0] in employee_id_mapping:
        last10_employee_fh.write("%s\t%s\n" % (employee_id_mapping[kv[0]], str(kv[1])))
last10_employee_fh.close()




group_score = {}
top10_group_fh = open("data/output_top10_group.txt", "w", encoding="utf-8")
for group in group_employee:
    group_sum = 0
    count = 0
    for employee in group_employee[group]:
        if employee in employee_score_dict:
            group_sum += employee_score_dict[employee]
        count += 1
    group_score[group] = group_sum / count

group_score["A5-林鋅江"] += 5
group_score["A6-林秀薇"] += 1
group_score["B5-王文昭"] += 5
group_score["E1-張雅婷"] += 1
group_score["C3-鄧蓓"] += 2
group_score["F6-汪興一"] += 3
group_score["D3-王金良"] += 1


sorted_by_value = sorted(group_score.items(), key=lambda kv: kv[1], reverse=True)

count = 0
for kv in sorted_by_value:
    if count < 10:
        top10_group_fh.write("%s\t%s\n" % (kv[0], kv[1]))
        count += 1
    continue
top10_group_fh.close()

last10_group_fh = open("data/output_last10_group.txt", "w", encoding="utf-8")
sorted_by_value = sorted(group_score.items(), key=lambda kv: kv[1], reverse=True)
for kv in sorted_by_value:
    last10_group_fh.write("%s\t%s\n" % (kv[0], kv[1]))
last10_group_fh.close()