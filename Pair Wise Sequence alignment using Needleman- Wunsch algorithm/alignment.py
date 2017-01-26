import numpy as np
import copy
f1 = open('file1.txt')
f2 = open('file2.txt')
sequence1=''
sequence2=''
num_linesf1 = sum(1 for line in open('file1.txt'))
num_linesf2 = sum(1 for line in open('file2.txt'))

# print f2.readlines()[1]
print "Enter the value for gap"
gap=int(raw_input())
print "Enter the value for match"
match=int(raw_input())
print "Enter the value for mismatch"
mismatch=int(raw_input())
for p in range(1,num_linesf1):
	sequence1=sequence1+f1.readlines()[p]


for q in range(1,num_linesf2): 
	temp=f2.readlines()[q]
	sequence2=sequence2+temp
# a=f1.readlines()
# b=f2.readlines()
# print num_linesf1,num_linesf2
# sequence1=a[1]
# sequence2=b[1]
print sequence1
print sequence2
newseq1=[]
newseq2=[]
length1=len(sequence1)
length2=len(sequence2)
# print length1


matrix = np.zeros((length1+1,length2+1))
directionmatrix=copy.copy(matrix)

for i in range(1,length1+1):
	matrix[i][0]=gap+matrix[i-1][0]
for i in range(1,length2+1):
	matrix[0][i]=gap+matrix[0][i-1]

for i in range(1,length1+1):
	for j in range(1,length2+1):
		if(sequence1[i-1]==sequence2[j-1]):
			score=match
		else:
			score=mismatch
		choice1=matrix[i-1][j-1]+score
		choice2=matrix[i-1][j]+gap
		choice3=matrix[i][j-1]+gap
		matrix[i][j]= max(choice1,choice2,choice3)
		if(matrix[i][j]==choice1):
			directionmatrix[i][j]=0 #diagonal
		elif (matrix[i][j]==choice2):
			directionmatrix[i][j]=1#vertical
		else:
			directionmatrix[i][j]=-1 #horizontal
			
print "Scoring Matrix is "
print "%s"%matrix
print "Direction Matrix is "
print "%s"%directionmatrix
optscore=matrix.max()
# length1=length1-1
# length2=length2-1
while(length1>0 or length2>0):
	if(length2==0):
		newseq1=[sequence1[length1-1]]+newseq1
		newseq2=['--']+newseq2
		length1=length1-1
		break
		
	if(length1==0):
		newseq1=['--']+newseq1
		newseq2=[sequence2[length2-1]]+newseq2
		length2=length2-1
		break
	if (directionmatrix[length1,length2]==0):
		newseq1=[sequence1[length1-1]]+newseq1
		newseq2=[sequence2[length2-1]]+newseq2
		length1=length1-1
		length2=length2-1
	elif(directionmatrix[length1][length2]==1):
		newseq1=[sequence1[length1-1]]+newseq1
		newseq2=['--']+newseq2
		length1=length1-1
	else:
		newseq1=['--']+newseq1
		newseq2=[sequence2[length2-1]]+newseq2
		length2=length2-1
	# print length1
	# print length2
print "Optimal Allignments are"	
print newseq1
print newseq2
print "The Optimal score is %s"%optscore
	
