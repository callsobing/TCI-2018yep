import xlrd
from opencc import OpenCC
import re

customer_info = {}
#讀入試題統計
wb = xlrd.open_workbook("../data/20181227考试测试_云课堂(考试结果)_Min.Yue_20190107161730 (1).xlsx", on_demand=True)

sheet = wb.sheet_by_name("正考结果")
customer_service_pair = {}
nrows = sheet.nrows
employee_score_dict = {}

for i in range(1, nrows):
    row_data = sheet.row_values(i)
    employee_name = row_data[1]
    employee_score = float(row_data[5])
    employee_score_dict[employee_name] = employee_score

#讀入考試結果
wb = xlrd.open_workbook("../data/20181227考试测试_云课堂(考试结果)_Min.Yue_20190107161730 (1).xlsx", on_demand=True)

sheet = wb.sheet_by_name("正考结果")
customer_service_pair = {}
nrows = sheet.nrows
employee_score_dict = {}

for i in range(1, nrows):
    row_data = sheet.row_values(i)
    employee_name = row_data[1]
    employee_score = float(row_data[5])
    employee_score_dict[employee_name] = employee_score

#讀入分組名單
wb = xlrd.open_workbook("../data/20181227考试测试_云课堂(考试结果)_Min.Yue_20190107161730 (1).xlsx", on_demand=True)

sheet = wb.sheet_by_name("正考结果")
customer_service_pair = {}
nrows = sheet.nrows
employee_score_dict = {}

for i in range(1, nrows):
    row_data = sheet.row_values(i)
    employee_name = row_data[1]
    employee_score = float(row_data[5])
    employee_score_dict[employee_name] = employee_score



output_fh = open("../data/employee_ranking.txt", "w", encoding="UTF-8")
employee_score_dict_sorted = sorted(employee_score_dict.items(), key=lambda kv: kv[1], reverse=True)
for kv_pair in employee_score_dict_sorted:
    regex = re.compile(r"\((\S+)\)")
    match = regex.search(kv_pair[0])
    cc = OpenCC('t2s')
    try:
        output_fh.write("%s\t%s\n" % (cc.convert(match[1]), kv_pair[1]))
        print("%s\t%s" % (cc.convert(match[1]), kv_pair[1]))
    except:
        output_fh.write("%s\t%s\n" % (cc.convert(kv_pair[0]), kv_pair[1]))

output_fh.close()

