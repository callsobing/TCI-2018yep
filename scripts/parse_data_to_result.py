import xlrd


employee_id_mapping = {}
group_employee = {}

employee_group_fh = open("data/employ_id_mapping", encoding="utf-8")
for line in employee_group_fh:
    line = line.rstrip()
    splitted = line.split("\t")
    employee_id_mapping[splitted[1]] = splitted[0]

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
    prob_score_pair[row_data[0]] = row_data[3]

prob_score_pair_sorted = sorted(prob_score_pair.items(), key=lambda kv: kv[1])

count = 0

for pair in prob_score_pair_sorted:
    if count < 6:
        count += 1
        output_fh = open("data/output_question%s.txt" % str(count), "w", encoding="utf-8")
        prob_title = sheet.cell_value(int(pair[0]), 1)
        output_fh.write("%.0f\t%s\t%s\n" % (float(pair[0]), prob_title, str(pair[1])))
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
    employee_score_dict[employee_name] = employee_score

for i in range(1, 11):
    row_data = sheet.row_values(i)
    employee_id = row_data[2]
    if row_data[5] == "":
        row_data[5] = 0.0
    employee_score = float(row_data[5])
    top10_employee[employee_id] = employee_score

top10_employee_fh = open("data/output_top10_employee.txt", "w", encoding="utf-8")
sorted_by_value = sorted(top10_employee.items(), key=lambda kv: kv[1], reverse=True)
for kv in sorted_by_value:
    top10_employee_fh.write("%s\t%s\n" % (employee_id_mapping[kv[0]], str(kv[1])))
top10_employee_fh.close()


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

sorted_by_value = sorted(group_score.items(), key=lambda kv: kv[1], reverse=True)

count = 0
for kv in sorted_by_value:
    if count < 10:
        top10_group_fh.write("%s\t%s\n" % (kv[0], kv[1]))
        count += 1
    continue
top10_group_fh.close()