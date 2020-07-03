import numpy as np  
from function import *
from load import *
import pandas as pd
import json
import matplotlib.pyplot as plt
from sklearn import preprocessing
from sklearn.model_selection import train_test_split



#[X, y] = Loadtxt('a.txt')
pdf=pd.read_csv('train_DN.csv',header=None)
y=pdf.values[:,5]
X = np.zeros((np.size(y),5))
X[:,0] = 1
X[:,1:]=pdf.values[:,1:5]


Theta = NormEqn(X,y)
#[Theta1, J_hist] = GradientDescent(X,y,0.01,5000)
#Sprint(Theta1)

pdf=pd.read_csv('test_DN.csv',header=None)
y1=pdf.values[:,5]
X1 = np.zeros((np.size(y1),5))
X1[:,0] = 1
X1[:,1:]=pdf.values[:,1:5]

print(X1)
predict = predict(X1, Theta)
sai_so=-predict+y1
sai_so=sai_so*100
for i in range(0,np.size(y1)):
	if(sai_so[i]<0): sai_so[i]=sai_so[i]*(-1)
	if(predict[i]<0): predict[i]=predict[i]*(-1)
print(predict)
print(y1)
print((sai_so))
mua1=0
komua1=0
mua=0
komua=0
for i in range(0,np.size(y1)):
	if(predict[i]>0.025): mua=mua+1
	else: komua=komua+1
for i in range(0,np.size(y1)):
	if(y[i]>0.025): mua1=mua1+1
	else: komua1=komua1+1

print('tong train set:%d'%np.size(y1))
print('so ngay mua du doan:%d'%mua)
print('so ngay khong mua du doan:%d'%komua)
print('so ngay mua thuc:%d'%mua1)
print('so ngay ko mua thuc:%d'%komua1)

loss=computeCost(X1,y1,Theta)
loss2=computeCost_Vec(X,y,Theta)
print('loss function:%f'%loss)
print('loss function:%f'%loss2)
#print(Theta)
#plt.plot(X[:,1:],y,'rx')

#vẻ loss
#[Theta1, J_hist] = GradientDescent(X1,y1,0.01,500)
#print(Theta1)
plt.figure(1)
plt.plot(X1[:,1],y1,'rx')
plt.plot(X1[:,1],predict,'-b') #đơn vị gốc: nghìn người
#plt.figure(2)
#plt.plot(J_hist[:,0],J_hist[:,1],'-r')
plt.show()



from sklearn import model_selection
from sklearn.linear_model import LinearRegression as L
import joblib
import pickle

"""dieuChinh = preprocessing.MinMaxScaler(feature_range= (0,1))
X_dieuChinh = dieuChinh.fit_transform(X)
X1_dieuChinh = dieuChinh.fit_transform(X1)

y=y.astype('float')
print(y)
model = L()
model.fit(X_dieuChinh, y)

filename = 'finalized_model1.sav'
#joblib.dump(model, filename)
pickle.dump(model, open(filename, 'wb'))

loaded_model = pickle.load(open(filename, 'rb'))
#loaded_model = joblib.load(filename)
#loss = loaded_model.score(X1, y1)

result = loaded_model.predict(X_dieuChinh)
print(result)
#print(loss)




#print()
#print('xac suat mưa: %f'%(predict))

"""
"""[X, mu, s] = Normalize(X)
#[Theta, J_hist] = GradientDescent(X,y,0.1,5000)
X2=X1
X2 = (X2-mu)/s
X2[:,0] = 1
#Lưu ý sửa lại x0 = 1
print(X2)
predict5 = predict(X2, Theta)
#print('%f'%(predict))
"""
"""
[X, mu, s] = Normalize(X)
input = X1
input = (input-mu)/s
#Lưu ý sửa lại x0 = 1
input[:,0] = 1
print(input)
p = input @ Theta
print(p+0.5)"""