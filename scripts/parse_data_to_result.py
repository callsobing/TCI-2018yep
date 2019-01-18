import xlrd
from opencc import OpenCC
import re

customer_info = {}
# 讀入試題統計
# 题号	题目	应得分	实得分	正确率	满分人数	零分人数	部分得分人数
wb = xlrd.open_workbook("../data/20181227考试测试_云课堂(试题统计)_Min.Yue_20190107161736 (1).xlsx", on_demand=True)

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
    if count < 10:
        prob_title = sheet.cell_value(int(pair[0]), 1)
        print("%.0f\t%s\t%s" % (float(pair[0]), prob_title, str(pair[1])))
        count += 1
    continue

total_avg = scored_points/full_score
print("%.2f" % (total_avg * 100))


# 讀入考試結果
# 排名	姓名	账号	手机号	部门	得分	是否通过	交卷时间	考试用时（分钟）
wb = xlrd.open_workbook("../data/20181227考试测试_云课堂(考试结果)_Min.Yue_20190107161730 (1).xlsx", on_demand=True)

sheet = wb.sheet_by_name("正考结果")
top10_employee = {}
employee_score_dict = {}

for i in range(1, sheet.nrows):
    row_data = sheet.row_values(i)
    employee_name = row_data[1]
    employee_score = float(row_data[5])
    employee_score_dict[employee_name] = employee_score

for i in range(1, 11):
    row_data = sheet.row_values(i)
    employee_name = row_data[1]
    print(row_data[5])
    employee_score = float(row_data[5])
    top10_employee[employee_name] = employee_score

for employee in top10_employee:
    print("%s\t%s" % (employee, str(top10_employee[employee])))

#
# #讀入分組名單
# group_fh = open("../data/第一講堂分組_0118.txt", encoding="utf-8")
#
# for data in group_fh:
#     row_data = sheet.row_values(i)
#     employee_name = row_data[1]
#     employee_score = float(row_data[5])
#     employee_score_dict[employee_name] = employee_score
#
#
#
# output_fh = open("../data/employee_ranking.txt", "w", encoding="UTF-8")
# employee_score_dict_sorted = sorted(employee_score_dict.items(), key=lambda kv: kv[1], reverse=True)
# for kv_pair in employee_score_dict_sorted:
#     regex = re.compile(r"\((\S+)\)")
#     match = regex.search(kv_pair[0])
#     cc = OpenCC('t2s')
#     try:
#         output_fh.write("%s\t%s\n" % (cc.convert(match[1]), kv_pair[1]))
#         print("%s\t%s" % (cc.convert(match[1]), kv_pair[1]))
#     except:
#         output_fh.write("%s\t%s\n" % (cc.convert(kv_pair[0]), kv_pair[1]))
#
# output_fh.close()
#
