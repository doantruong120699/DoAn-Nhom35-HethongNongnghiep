import numpy as np  
def Loadtxt(path):
    try:
        raw = np.loadtxt('a.txt',delimiter = ',')
        y = raw[:,3]
        #tạo ma trận X trống
        X = np.zeros((np.size(y),np.size(raw,1)))
        #thêm 1 vào cột đầu
        X[:,0] = 1
        #Thêm 3 cột sau vào
        X[:,1:] = raw[:,0:3]
        yield X
        yield y
    except:
        return 0
def Loadtxt1(path):
    try:
        raw = np.loadtxt('b.txt',delimiter = ',')
        y = raw[:,3]
        #tạo ma trận X trống
        X = np.zeros((np.size(y),np.size(raw,1)))
        #thêm 1 vào cột đầu
        X[:,0] = 1
        #Thêm 3 cột sau vào
        X[:,1:] = raw[:,0:3]
        yield X
        yield y
    except:
        return 0