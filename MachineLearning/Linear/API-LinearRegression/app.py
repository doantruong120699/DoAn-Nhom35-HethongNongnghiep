from flask import Flask, render_template, request, redirect, url_for, jsonify   
import numpy as np
import pandas as pd
from sklearn import preprocessing
#from keras.models import load_model, model_from_json
#from sklearn.preprocessing import MinMaxScaler, StandardScaler
import json, requests
from flask import json
import joblib
#import pickle
app = Flask(__name__)
# pipenv run python main.pyp
@app.route('/response')
def reponse():
    data = []
    r = requests.get('https://test-cqt-esp8266.000webhostapp.com/api/product/read.php')
    res = json.loads(r.text)
    print(type(res))
    for x in res: #{'wether': []}
        #print(x, ": ", type(res.get(x)),len(res.get(x)))
        print(x, ":::" , res.get(x))
        for index in res.get(x):
            print(index, ":::", type(index)) 
            for j in index:
                data.append(index.get(j))
                print(j, "::", index.get(j)) 
    print(data)
    return data

@app.route('/', methods=['GET'])
def getParameter():
    data_recieve=reponse()

    loaded_model = joblib.load('finalized_model3.sav')
    time = data_recieve[1]
    _temp = float(data_recieve[2])
    _doam = float(data_recieve[3])
    _gio = float(data_recieve[5])
    _apsuat = float(data_recieve[4])
    data = np.array([[1,_temp, _doam, _apsuat, _gio]], dtype=np.float32)
    #data = np.array([[1,34,60, 1003, 1]], dtype=np.float32)
    print("Data: ", data)
    prediction = loaded_model.predict(data)
    print(prediction[0]);
    if(prediction[0]<0) : prediction+=(-prediction)
    results=[]
    results.append(prediction[0])
    s = str(prediction[0])
    x = {"Time": time, "luongmua": s}
    js = json.dumps(x)
    print(js)
    return js


if __name__ == "__main__":
    print("Loading...........")
    app.run(threaded=True)