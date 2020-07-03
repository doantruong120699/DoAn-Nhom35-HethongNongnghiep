from flask import Flask, render_template, request, redirect 
import numpy as np
import pandas as pd
from keras.models import load_model, model_from_json
from sklearn.preprocessing import MinMaxScaler, StandardScaler
import tensorflow as tf
import json, requests

app = Flask(__name__)
# pipenv run python main.py

def change_time(dataset):
    time_data = dataset['timestamp']
    time = []
    for i in range (len(time_data)):
        t = time_data[i].split('T', 1)
        time.append((t[1]))
    return time

def getdata(data):
    data_output = data[['Precipitation Total']].values
    data_input = data[['Temperature', 'Relative Humidity', 'Mean Sea Level Pressure', 'Wind Speed', 'timestamp_0000', 'timestamp_0100', 'timestamp_0200', 'timestamp_0300', 'timestamp_0400', 'timestamp_0500', 'timestamp_0600', 'timestamp_0700', 'timestamp_0800', 'timestamp_0900', 'timestamp_1000', 'timestamp_1100', 'timestamp_1200', 'timestamp_1300', 'timestamp_1400', 'timestamp_1500', 'timestamp_1600', 'timestamp_1700', 'timestamp_1800', 'timestamp_1900', 'timestamp_2000', 'timestamp_2100', 'timestamp_2200', 'timestamp_2300']].values
    return data_output, data_input


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

def init_rain():
    global model_rain, graph1
    print("Load rain")
    # load json and create model
    graph1 = tf.get_default_graph()
    json_file = open('model_rain.json', 'r')
    loaded_model_json = json_file.read()
    json_file.close()
    loaded_model = model_from_json(loaded_model_json)
    # load weights into new model
    loaded_model.load_weights("model_rain.h5")
    loaded_model.save('model_rain.hdf5')
    model_rain=load_model('model_rain.hdf5')

@app.route('/', methods=['GET'])
def hi():

    print("Load rain")
    # load json and create model
    json_file = open('model_rain.json', 'r')
    loaded_model_json = json_file.read()
    json_file.close()
    loaded_model = model_from_json(loaded_model_json)
    # load weights into new model
    loaded_model.load_weights("model_rain.h5")
    loaded_model.save('model_rain.hdf5')
    model_rain=load_model('model_rain.hdf5')
    print('Loaded model!')

    # Read Data sent from database
    data_recieve = reponse()
    dataset = pd.read_csv('Data-training.csv')
    dataset['timestamp'] = change_time(dataset)
    data = pd.get_dummies(dataset[['timestamp', 'Temperature', 'Relative Humidity', 'Mean Sea Level Pressure', 'Wind Speed', 'Precipitation Total']])
    data_output, data_input = getdata(data)
    sc = StandardScaler()
    x_train = sc.fit_transform(data_input)

    time = data_recieve[1]
    t = time.split(' ', 1)
    t = t[1].split(':', 1)
    timedata = (int(t[0]))
    print(timedata)

    _temp = float(data_recieve[2])
    _doam = float(data_recieve[3])
    _gio = float(data_recieve[5])
    _apsuat = float(data_recieve[4])

    data = []
    data.append(_temp)
    data.append(_doam)
    data.append(_apsuat)
    data.append(_gio)

    for i in range(24):
        if(i == timedata): 
            data.append(1)
        else: 
            data.append(0)
    print(data)
    data = np.array(data, dtype=np.float32)
    data = data.reshape(1, -1)
    print("Data: ", data)
    data = sc.transform(data)
    print("Data Standar: ", data)
    prediction = model_rain.predict(data)
    if(prediction[0][0]<0.1):
        s = str(0.0)
    else:
        s = str(prediction[0][0])
    tem = str(_temp)
    doam = str(_doam)
    gio = str(_gio)
    apsuat = str(_apsuat)
    x = {"Time": time, "Nhietdo": tem, "Doam": doam, "apsuat": apsuat, "tocdogio": gio, "luongmua": s}
    js = json.dumps(x)
    print(js)
    return js

if __name__ == "__main__":
    print("Loading...........")
    app.run(threaded=True)

 