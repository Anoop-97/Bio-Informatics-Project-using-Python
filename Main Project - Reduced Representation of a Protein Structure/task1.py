file = raw_input("Please enter a filename that need to be processed: ")
filename = file + ".pdb"
data = open(filename,"r")
output = open("output.pdb","w")

data.readline()
for line in data:
	if 'ATOM' in line:
		if 'CA' in line:
			print line
			output.write(line)